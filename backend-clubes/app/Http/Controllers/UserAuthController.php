<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class UserAuthController extends Controller
{
    /**
     * Registrar usuario
     */
    public function register(Request $request)
    {
        $request->validate([
            'rut'       => 'required|string|unique:users,rut',
            'name'      => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'team_id'   => 'nullable|exists:teams,id',
            'role'      => 'in:superuser,teacher,team_admin'
        ]);

        $user = User::create($request->all());

        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'user'    => $user
        ], 201);
    }

    /**
     * Login de usuario (CORREGIDO)
     */
    public function login(Request $request)
    {
        // Validación de datos
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // Buscar usuario
        $user = User::where('email', $request->email)->first();

        // Verificar contraseña correctamente
        if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'Credenciales incorrectas'
            ], 401);
        }
        
        // 🗑️ Eliminar tokens anteriores (Buena práctica al iniciar sesión)
        $user->tokens()->delete();

        // Crear access token
        $accessTokenResult = $user->createToken('access_token');
        $accessToken = $accessTokenResult->plainTextToken;

        // Crear refresh token con habilidad 'refresh'
        $refreshTokenResult = $user->createToken('refresh_token', ['refresh']);
        $refreshToken = $refreshTokenResult->plainTextToken;

        // Asignar type y expiración al modelo de refresh token
        $refresh = $refreshTokenResult->accessToken; // modelo PersonalAccessToken
        
        // 🎯 CORRECCIÓN CRÍTICA 1: Asignar explícitamente el 'type' a 'refresh'
        // Esto coincide con la columna ENUM añadida en tu migración.
        $refresh->type = 'refresh'; 
        
        $refresh->expires_at = now()->addDays(7);
        $refresh->save();

        // Responder con tokens y usuario
        return response()->json([
            'message'       => 'Login exitoso',
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
            'user'          => $user
        ]);
    }


    /**
     * Perfil del usuario autenticado
     */
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Logout: eliminar todos los tokens del usuario
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout exitoso'
        ]);
    }

    /**
     * Refrescar tokens (CORREGIDO)
     */
    public function refresh(Request $request)
    {
        $request->validate(['refresh_token' => 'required']);

        $hashed = hash('sha256', $request->refresh_token);

        // CRÍTICO: Buscar por 'name', 'type' y verificar la expiración.
        $refreshTokenModel = PersonalAccessToken::where('token', $hashed)
            ->where('name', 'refresh_token') // Busca el nombre
            ->where('type', 'refresh')       // 🎯 CORRECCIÓN CRÍTICA 2: Busca el 'type' correcto
            ->where('expires_at', '>', now())
            ->first();

        if (!$refreshTokenModel) {
            // Este es el mensaje que recibías: token no existe, expiró o no es de tipo 'refresh'.
            return response()->json(['message' => 'Refresh token inválido'], 401); 
        }

        $user = $refreshTokenModel->tokenable;

        // --- Rotación de Tokens ---

        // 1. Eliminar el refresh token antiguo (Invalidación)
        $refreshTokenModel->delete();

        // 2. Crear un nuevo Access Token
        $newAccessToken = $user->createToken('access_token')->plainTextToken;

        // 3. Crear un nuevo Refresh Token (Rotación)
        $newRefreshTokenResult = $user->createToken('refresh_token', ['refresh']);
        $newRefreshToken = $newRefreshTokenResult->plainTextToken;

        // 4. Asignar expiración y type al nuevo refresh token
        $newRefreshTokenModel = $newRefreshTokenResult->accessToken; 
        
        // 🎯 Asegurar el 'type' también para el token rotado
        $newRefreshTokenModel->type = 'refresh'; 
        
        $newRefreshTokenModel->expires_at = now()->addDays(7);
        $newRefreshTokenModel->save();

        // 5. Responder con el nuevo par de tokens
        return response()->json([
            'message'        => 'Tokens refrescados exitosamente',
            'access_token'   => $newAccessToken,
            'refresh_token'  => $newRefreshToken,
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Login de usuario
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !password_verify($request->password, $user->password)) {
            return response()->json([
                'error' => 'Credenciales incorrectas'
            ], 401);
        }

        // Crear token
        $token = $user->createToken('user_token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'token'   => $token,
            'user'    => $user
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
}

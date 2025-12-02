<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  
    public function index()
    {
        $user = auth('api')->user();

        if ($user->role === 'superuser') {
            return response()->json(User::all(), 200);
        }

         if ($user->role === 'team') {
        return response()->json(
            User::where('team_id', $user->team_id)
                ->where('role', '!=', 'superuser')
                ->get(),
            200
        );
    }

     
        if ($user->role === 'teacher') {
            return response()->json(
                User::where('id', $user->id)->get(),
                200
            );
        }

        return response()->json([], 403);
    }

 
    public function register(Request $request)
    {
        $user = auth('api')->user();

      
        if (!in_array($user->role, ['superuser', 'team'])) {
            return response()->json(['message' => 'No autorizado'], 403);
        }


        $validator = Validator::make($request->all(), [
            'rut'       => 'required|string|unique:users,rut',
            'name'      => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6',
            'team_id'   => 'nullable|exists:teams,id',
            'role'      => 'required|in:superuser,teacher,team',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

       
        if ($user->role === 'team') {
          
            if ($request->team_id != $user->team_id) {
                return response()->json(['message' => 'No puedes crear usuarios fuera de tu team'], 403);
            }

           
            if ($request->role === 'superuser') {
                return response()->json(['message' => 'No puedes crear superusers'], 403);
            }
        }

        $newUser = User::create([
            'rut'       => $request->get('rut'),
            'name'      => $request->get('name'),
            'lastname'  => $request->get('lastname'),
            'email'     => $request->get('email'),
            'password'  => bcrypt($request->get('password')),
            'team_id'   => $request->get('team_id'),
            'role'      => $request->get('role'),
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $newUser
        ], 201);
    }

  
    public function show($rut)
    {
        $user = auth('api')->user();
        $targetUser = User::where('rut', $rut)->first();

        if (!$targetUser) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->role === 'superuser') {
            return response()->json($targetUser, 200);
        }

        if ($user->role === 'team' && $targetUser->team_id != $user->team_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($user->role === 'teacher' && $targetUser->id != $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($targetUser, 200);
    }


    public function update(Request $request, $rut)
    {
        $user = auth('api')->user();
        $targetUser = User::where('rut', $rut)->first();

        if (!$targetUser) {
            return response()->json(['message' => 'User not found'], 404);
        }

   
        if ($user->role === 'team') {
          
            if ($targetUser->team_id != $user->team_id) {
                return response()->json(['message' => 'Forbidden'], 403);
            }

            if ($targetUser->role === 'superuser') {
                return response()->json(['message' => 'No puedes editar superusers'], 403);
            }
        }

        if ($user->role === 'teacher') {
          
            if ($targetUser->id != $user->id) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
        }

     
        $validator = Validator::make($request->all(), [
            'name'      => 'sometimes|string|max:255',
            'lastname'  => 'sometimes|string|max:255',
            'email'     => 'sometimes|email|unique:users,email,' . $targetUser->id,
            'password'  => 'sometimes|string|min:6',
            'team_id'   => 'sometimes|nullable|exists:teams,id',
            'role'      => 'sometimes|in:superuser,teacher,team',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

  
        $dataToUpdate = $request->only([
            'name', 'lastname', 'email', 'team_id', 'role'
        ]);


        if ($request->has('password') && !empty($request->password)) {
            $dataToUpdate['password'] = bcrypt($request->password);
        }

   
        if ($user->role === 'team' && isset($dataToUpdate['role']) && $dataToUpdate['role'] === 'superuser') {
            return response()->json(['message' => 'No puedes asignar rol superuser'], 403);
        }

        if ($user->role === 'team' && isset($dataToUpdate['team_id']) && $dataToUpdate['team_id'] != $user->team_id) {
            return response()->json(['message' => 'No puedes mover usuarios a otro team'], 403);
        }

        $targetUser->update($dataToUpdate);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $targetUser
        ], 200);
    }


    public function search(Request $request)
    {
        $user = auth('api')->user();
        $query = $request->query('search');

        $q = User::where(function($s) use ($query) {
            $s->where('name', 'like', "%$query%")
              ->orWhere('lastname', 'like', "%$query%")
              ->orWhere('rut', 'like', "%$query%")
              ->orWhere('email', 'like', "%$query%");
        });

        if ($user->role === 'team') {
            $q->where('team_id', $user->team_id);
        }

        if ($user->role === 'teacher') {
            $q->where('id', $user->id);
        }

        return response()->json($q->get(), 200);
    }

  
    public function destroy($rut)
    {
        $user = auth('api')->user();
        $targetUser = User::where('rut', $rut)->first();

        if (!$targetUser) {
            return response()->json(['message' => 'User not found'], 404);
        }

     
        if ($targetUser->id === $user->id) {
            return response()->json(['message' => 'No puedes eliminar tu propio usuario'], 403);
        }

     
        if ($user->role === 'team') {
            if ($targetUser->team_id != $user->team_id) {
                return response()->json(['message' => 'Forbidden'], 403);
            }

            if ($targetUser->role === 'superuser') {
                return response()->json(['message' => 'No puedes eliminar superusers'], 403);
            }
        }

        if ($user->role === 'teacher') {
            return response()->json(['message' => 'No tienes permisos para eliminar usuarios'], 403);
        }

        $targetUser->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
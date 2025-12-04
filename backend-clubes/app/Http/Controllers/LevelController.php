<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
  
    public function index(Request $request)
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->role === 'superuser') {
            $levels = Level::select('id', 'name', 'user_id', 'team_id')->get();
        } 
        else if ($user->role === 'team') {
            $levels = Level::where('team_id', $user->team_id)
                           ->select('id', 'name', 'user_id', 'team_id')
                           ->get();
        } 
        else {
            return response()->json([], 403);
        }

        return response()->json($levels, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:1|max:100',
            'user_id' => 'required|numeric',
            'team_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        Level::create([
            'name' => $request->name,
            'user_id' => $request->user_id,
            'team_id' => $request->team_id,
        ]);

        return response()->json(['message' => 'Nivel añadido correctamente'], 201);
    }


    public function edit(string $id)
    {
        $level = Level::find($id);

        if (!$level) {
            return response()->json(['message' => 'Nivel no encontrado'], 404);
        }

        return response()->json($level, 200);
    }

    public function update(Request $request, string $id)
    {
        $level = Level::find($id);

        if (!$level) {
            return response()->json(['message' => 'Nivel no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:1|max:100',
            'user_id' => 'required|numeric',
            'team_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $level->name = $request->name;
        $level->user_id = $request->user_id;
        $level->team_id = $request->team_id;

        $level->save();

        return response()->json(['message' => 'Nivel actualizado correctamente'], 200);
    }

    public function destroy($id)
    {
        $level = Level::find($id);

        if (!$level) {
            return response()->json(['message' => 'Nivel no encontrado'], 404);
        }

      
        if ($level->students()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar el nivel porque tiene estudiantes asociados.'
            ], 409);
        }

        $level->delete();

        return response()->json([
            'message' => 'Nivel eliminado correctamente'
        ], 200);
    }
}

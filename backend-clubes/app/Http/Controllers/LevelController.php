<?php

namespace App\Http\Controllers;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
   
    public function index()
    {
        return Level::all();
    }


   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'team_id' => 'required|exists:teams,id',
            'user_id' => 'required|exists:users,id'
    ]);
      
    $level = Level::create($validated);

    
    return response()->json([
        'message' => 'Level creado correctamente',
        'level'   => $level
    ], 201);
    }

  
    public function show(string $id)
    {
          $level = Level::find($id);

        if (!$level) {
            return response()->json(['message' => 'Nivel no encontrado'], 404);
        }

        return $level;
    }

  

   
    public function update(Request $request, string $id)
    {
       
    $level = Level::findOrFail($id);

   
    $validated = $request->validate([
        'name'    => 'sometimes|required|string|max:255',
        'team_id' => 'sometimes|required|exists:teams,id',
        'user_id' => 'sometimes|required|exists:users,id'
    ]);

  
    $level->update($validated);

   
    return response()->json([
        'message' => 'Level actualizado correctamente',
        'level'   => $level
    ], 200);
    }

  
    public function destroy(string $id)
    {
        $level = Level::findOrFail($id);
        $level->delete();
        return response()->json(['message'=>'Nivel Eliminado']);
    }
}

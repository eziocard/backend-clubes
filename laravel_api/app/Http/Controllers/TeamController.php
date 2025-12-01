<?php

namespace App\Http\Controllers;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $team = Team::all();
        return response()->json($team,200);
    }

  
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'image' => 'nullable|string',
            'state' => 'required|boolean',
        ]);
        $team = Team::create($validated);
        return response()->json($team,201);
    }

    public function show(string $id)
    {
        $team = Team::find($id);

        if (!$team) {
            return response()->json(['message' => 'Equipo no encontrado'], 404);
        }

        return $team;
    }

    public function search(Request $request)
{
    $query = $request->get('search');

    $teams = Team::where('name', 'LIKE', "%{$query}%")
        ->limit(10)
        ->get();

    return response()->json($teams);
}


   public function update(Request $request, string $id)
{
    $team = Team::findOrFail($id); 

    $validated = $request->validate([
        'name'=> 'required|string|max:255|unique:teams,name,' . $id,
        'email'=> 'required|email|unique:teams,email,' . $id,
        'image'=> 'nullable|string',
        'state'=> 'boolean'
    ]);

    $team->update($validated);

    return response()->json($team);
}

   
    public function destroy(string $id)
    {
   
        $team = Team::findOrFail($id);
        $team->delete();
        return response()->json(['message'=>'Equipo eliminado correctamente']);
    }
}

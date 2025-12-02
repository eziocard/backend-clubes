<?php

namespace App\Http\Controllers;
use App\Models\Team; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    
    public function index()
    {
        $teams = Team::all();
        if($teams->isEmpty()){
            return response()->json(['message' => 'No Teams found'],404);
        }
        return response()->json($teams,200);
    }

   
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|email',
            'state' => 'required|boolean',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()],422);
        }
        Team::create([
            'name'=>$request->get('name'),
            'email'=>$request->get('email'),
            'state'=>$request->get('state'),
        ]);
        return response()->json(['message' => 'Team added successfully'],201);
    }


  
    public function edit(string $id)
    {
        $teams = Team::find($id);
        if(!$teams){
            return response()->json(['message'=>'Team not found'],404);
        }
        return response()->json($teams,200);
    }

    
    public function update(Request $request, string $id)
    {
        $teams = Team::find($id);
        if(!$teams){
            return response()->json(['message'=>'Team not found'],404);
        }
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|email',
            'state' => 'required|boolean',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()],422);
        }

        if($request->has('name')){
            $teams->name = $request->name;
        }
        if($request->has('email')){
            $teams->email = $request->email;
        }
        if($request->has('state')){
            $teams->state = $request->state;
        }

        $teams->update();
        return response()->json(['message'=>'Team updated successfully'],200);
    }

   
    public function destroy(string $id)
    {
        $teams = Team::find($id);
        if(!$teams){
            return response()->json(['message'=>'Team not found'],404);
        }
        $teams->delete();
    }
}

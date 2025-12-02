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
            $levels = Level::select('id', 'name')->get();
        } 
      
        else if ($user->role === 'team') {
            $levels = Level::where('team_id', $user->team_id)
                           ->select('id', 'name')
                           ->get();
        } 
  
        else {
            return response()->json([], 403);
        }

        return response()->json($levels, 200);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|min:1|max:100',
            'user_id' => 'required|numeric',
            'team_id' => 'required|numeric'
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
        Level::create([
            'name'=>$request->get('name'),
            'user_id'=>$request->get('user_id'),
            'team_id'=>$request->get('team_id'),
        ]);
        return response()->json(['message' => 'Level added successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Level $level)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Level $level)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level)
    {
        //
    }

}
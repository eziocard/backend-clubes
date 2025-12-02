<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{

    public function index()
    {
        $user = auth('api')->user();

       
        if ($user->role === 'superuser') {
            return response()->json(Student::all(), 200);
        }


        if ($user->role === 'team') {
            return response()->json(
                Student::where('team_id', $user->team_id)->get(),
                200
            );
        }

    
        if ($user->role === 'teacher') {

            $levels = Level::where('user_id', $user->id)->pluck('id');

            return response()->json(
                Student::whereIn('level_id', $levels)
                       ->where('team_id', $user->team_id)
                       ->get(),
                200
            );
        }

        return response()->json([], 403);
    }

    public function store(Request $request)
    {
        $user = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'rut'      => 'required|string|unique:students,rut',
            'name'     => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'age'      => 'required|integer|min:1|max:120',
            'level_id' => 'required|exists:levels,id',
            'team_id'  => 'required|exists:teams,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

     
        if ($user->role === 'team' && $request->team_id != $user->team_id) {
            return response()->json(['message' => 'No puedes crear estudiantes fuera de tu team'], 403);
        }

        if ($user->role === 'teacher') {

            $levels = Level::where('user_id', $user->id)->pluck('id');

            if (!$levels->contains($request->level_id)) {
                return response()->json(['message' => 'Nivel no autorizado'], 403);
            }

            if ($request->team_id != $user->team_id) {
                return response()->json(['message' => 'No autorizado en este team'], 403);
            }
        }

        Student::create($request->only([
            'rut','name','lastname','age','level_id','team_id'
        ]));

        return response()->json(['message' => 'Student created'], 201);
    }


    public function show($rut)
    {
        $user = auth('api')->user();
        $student = Student::find($rut);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

       
        if ($user->role === 'team' && $student->team_id != $user->team_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($user->role === 'teacher') {

            $levels = Level::where('user_id', $user->id)->pluck('id');

            if (!$levels->contains($student->level_id)) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
        }

        return response()->json($student, 200);
    }

   
    public function update(Request $request, $rut)
    {
        $user = auth('api')->user();
        $student = Student::find($rut);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

      
        if ($user->role === 'team' && $student->team_id != $user->team_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($user->role === 'teacher') {
            $levels = Level::where('user_id', $user->id)->pluck('id');

            if (!$levels->contains($student->level_id)) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
        }

       
        $validator = Validator::make($request->all(), [
            'name'     => 'sometimes|string|max:100',
            'lastname' => 'sometimes|string|max:100',
            'age'      => 'sometimes|integer|min:1|max:120',
            'level_id' => 'sometimes|exists:levels,id',
            'team_id'  => 'sometimes|exists:teams,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $student->update($request->only([
            'name','lastname','age','level_id','team_id'
        ]));

        return response()->json(['message' => 'Student updated'], 200);
    }


    public function search(Request $request)
    {
        $user = auth('api')->user();
        $query = $request->query('search');

        $q = Student::where(function($s) use ($query) {
            $s->where('name', 'like', "%$query%")
              ->orWhere('rut', 'like', "%$query%");
        });

        if ($user->role === 'team') {
            $q->where('team_id', $user->team_id);
        }

        if ($user->role === 'teacher') {
            $levels = Level::where('user_id', $user->id)->pluck('id');

            $q->whereIn('level_id', $levels)
              ->where('team_id', $user->team_id);
        }

        return response()->json($q->get(), 200);
    }


    public function destroy($rut)
    {
        $user = auth('api')->user();
        $student = Student::find($rut);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        if ($user->role === 'team' && $student->team_id != $user->team_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($user->role === 'teacher') {

            $levels = Level::where('user_id', $user->id)->pluck('id');

            if (!$levels->contains($student->level_id)) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
        }

        $student->delete();

        return response()->json(['message' => 'Student deleted'], 200);
    }
}

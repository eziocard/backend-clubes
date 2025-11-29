<?php

namespace App\Http\Controllers;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
   
    public function index()
    {
        $students = Student::all();
        return response()->json($students, 200);
    }

   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rut'      => 'required|string|unique:students,rut',
            'name'     => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'age'      => 'required|integer|min:1',
            'team_id'  => 'required|exists:teams,id',
            'level_id' => 'required|exists:levels,id'
        ]);

        $student = Student::create($validated);

        return response()->json([
            'message' => 'Alumno creado correctamente',
            'student' => $student
        ], 201);
    }

  
    public function show(string $id)
    {
        $student = Student::findOrFail($id);
        return response()->json($student, 200);
    }

     public function update(Request $request, string $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'lastname' => 'sometimes|required|string|max:255',
            'age'      => 'sometimes|required|integer|min:1',
            'team_id'  => 'sometimes|required|exists:teams,id',
            'level_id' => 'sometimes|required|exists:levels,id'
        ]);

        $student->update($validated);

        return response()->json([
            'message' => 'Estudiante actualizado correctamente',
            'student' => $student
        ], 200);
    }

    public function destroy(string $id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return response()->json([
            'message' => 'Estudiante eliminado correctamente'
        ], 200);
    }
}

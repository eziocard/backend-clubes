<?php

namespace App\Http\Controllers;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    
    public function index()
    {
        $attendances = Attendance::all();
        return response()->json($attendances, 200);
    }

    
    public function store(Request $request)
    {
         $validated = $request->validate([
            'student_rut' => 'required|exists:students,rut',
            'level_id'    => 'required|exists:levels,id',
            'date'        => 'required|date',
            'present'     => 'required|boolean',
            'user_id'     => 'required|exists:users,id'
        ]);

        $attendance = Attendance::create($validated);

        return response()->json([
            'message' => 'Asistencia registrada correctamente',
            'attendance' => $attendance
        ], 201);
    }

   
    public function show(string $id)
    {
        $attendance = Attendance::findOrFail($id);
        return response()->json($attendance, 200);
    }

    
   
    public function update(Request $request, string $id)
    {
         $attendance = Attendance::findOrFail($id);

        $validated = $request->validate([
            'student_rut' => 'sometimes|required|exists:students,rut',
            'level_id'    => 'sometimes|required|exists:levels,id',
            'date'        => 'sometimes|required|date',
            'present'     => 'sometimes|required|boolean',
            'user_id'     => 'sometimes|required|exists:users,id'
        ]);

        $attendance->update($validated);

        return response()->json([
            'message' => 'Asistencia actualizada correctamente',
            'attendance' => $attendance
        ], 200);
    }

   
    public function destroy(string $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return response()->json([
            'message' => 'Asistencia eliminada correctamente'
        ], 200);
    }
}

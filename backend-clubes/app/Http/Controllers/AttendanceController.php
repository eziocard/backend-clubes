<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    // Listar asistencias
    public function index(Request $request)
    {
        $user = auth('api')->user();

        $query = Attendance::with(['student', 'level', 'user']);

        // Si es teacher, solo su nivel
        if ($user->role === 'teacher') {
            $query->where('user_id', $user->id);
        }

        // Si es team, solo estudiantes de su team
        if ($user->role === 'team') {
            $query->whereHas('student', function($q) use ($user) {
                $q->where('team_id', $user->team_id);
            });
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        return response()->json($attendances, 200);
    }

    // Registrar asistencia
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_rut' => 'required|exists:students,rut',
            'level_id' => 'required|exists:levels,id',
            'present' => 'required|boolean',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = auth('api')->user();

        $attendance = Attendance::create([
            'student_rut' => $request->student_rut,
            'user_id' => $user->id,
            'level_id' => $request->level_id,
            'present' => $request->present,
            'date' => $request->date,
        ]);

        return response()->json(['message' => 'Asistencia registrada', 'attendance' => $attendance], 201);
    }
}

<?php

namespace App\Models;
use App\Models\Student;
use App\Models\Team;
use App\Models\Level;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'student_rut',
        'user_id',
        'level_id',
        'present',
        'date',
    ];

  
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_rut', 'rut');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}

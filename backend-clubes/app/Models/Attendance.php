<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['student_rut', 'level_id','date', 'present','user_id'];
    public function student()
    {

        return $this->belongsTo(Student::class, 'student_rut', 'rut');
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['name','team_id','teacher_rut'];
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}

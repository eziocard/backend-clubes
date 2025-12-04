<?php

namespace App\Models;
use App\Models\User;
use App\Models\Team;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['name','user_id','team_id'];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

   
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
     public function students()
    {
        return $this->hasMany(Student::class, 'level_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['name','team_id','user_id'];
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
{
    return $this->belongsTo(User::class, 'user_id', 'id');
}

    
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['name','user_id','team_id'];

    // R
    public function user()
    {
        return $this->belongsTo(User::class);
    }

   
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}

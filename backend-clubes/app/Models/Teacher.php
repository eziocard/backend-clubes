<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $primaryKey = 'rut';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['rut', 'name', 'lastname','team_id','level_id'];

      public function level()
    {
        return $this->belongsTo(Level::class);
    }
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}


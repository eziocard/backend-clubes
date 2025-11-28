<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $primaryKey = 'rut';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['rut', 'name', 'lastname','team_id'];

    public function levels()
{
    return $this->hasMany(Level::class, 'teacher_rut', 'rut');
}

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}


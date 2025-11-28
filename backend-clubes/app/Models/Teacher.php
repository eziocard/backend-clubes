<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
class Teacher extends Model
{
    protected $primaryKey = 'rut';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['rut', 'name', 'lastname','email','password','team_id'];
     protected $hidden = [
        'password'
    ];

     protected function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
    public function levels()
{
    return $this->hasMany(Level::class, 'teacher_rut', 'rut');
}

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}


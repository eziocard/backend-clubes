<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Team extends Model
{   
    
    protected $fillable = ['name','email','image','password','state'];
     protected $hidden = [
        'password'
    ];

     protected function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
    
    public function levels()
    {
        return $this->hasMany(Level::class);
    }


    public function students()
    {
        return $this->hasMany(Student::class);
    }
}

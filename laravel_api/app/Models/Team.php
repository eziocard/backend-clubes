<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{   
    
    protected $fillable = ['name','email','image','state'];

    
    public function levels()
    {
        return $this->hasMany(Level::class);
    }


    public function students()
    {
        return $this->hasMany(Student::class);
    }
}

<?php

namespace App\Models;
use App\Models\Student;
use App\Models\Level;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{   
    
    protected $fillable = ['name','email','state'];

    
    public function levels()
    {
        return $this->hasMany(Level::class);
    }


    public function students()
    {
        return $this->hasMany(Student::class);
    }
}

<?php

namespace App\Models;
use App\Models\Student;
use App\Models\Team;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $primaryKey = 'rut';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['rut', 'name', 'lastname', 'age','team_id','level_id'];
    
    public function level()
    {
        return $this->belongsTo(Level::class);
    }
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}

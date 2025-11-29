<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasApiTokens;

    protected $primaryKey = 'rut';
    public $incrementing = false; // rut no es autoincremental
    protected $keyType = 'string';

    protected $fillable = [
        'rut',
        'name',
        'lastname',
        'email',
        'password',
        'team_id',
        'role'
    ];

    protected $hidden = ['password'];

    
    public function setPasswordAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    // Relación: un usuario puede pertenecer a un equipo
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function levels()
    {
        return $this->hasMany(Level::class, 'user_rut', 'rut');
    }
}

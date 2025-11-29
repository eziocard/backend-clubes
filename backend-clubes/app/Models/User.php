<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

 

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
        return $this->hasMany(Level::class, 'user_id', 'id');
    }
}

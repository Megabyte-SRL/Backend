<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes, Notifiable;
    protected $fillable = [
        'email',
        'password',
        'rol'
    ];
    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function docente()
    {
        return $this->hasONe(Docente::class, 'usuario_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
        'avatar',
        'descripcion',
        'estado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relación con la tabla perfiles (uno a uno)
    public function perfil()
    {
        return $this->hasOne(Perfil::class, 'usuario_id');
    }

    // Relación con la tabla seguidores (uno a muchos)
    public function seguidores()
    {
        return $this->hasMany(Seguidores::class, 'seguido_id');
    }

    // Relación con los usuarios que sigue (uno a muchos inverso)
    public function siguiendo()
    {
        return $this->hasMany(Seguidores::class, 'usuario_id');
    }

    // Relación con publicaciones (uno a muchos)
    public function publicaciones()
    {
        return $this->hasMany(Publicacion::class, 'usuario_id');
    }
}
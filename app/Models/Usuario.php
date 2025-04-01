<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';  // Asegúrate de que se refiere a tu tabla 'usuarios'

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

    public function publicaciones()
    {
        return $this->hasMany(Publicacion::class, 'usuario_id');
    }

    // Si deseas hacer hashing de la password al momento de guardar:
    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            if ($user->password) {
                $user->password = bcrypt($user->password); // Hash de la password
            }
        });
    }
}

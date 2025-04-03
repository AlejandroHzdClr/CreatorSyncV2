<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    protected $table = 'perfiles';

    protected $fillable = [
        'usuario_id',
        'redes_sociales',
        'contenido_favorito',
        'web',
    ];

    // Relación con el usuario (uno a uno inverso)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
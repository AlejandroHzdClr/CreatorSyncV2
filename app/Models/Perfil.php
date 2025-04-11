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
        'contenido_favorito',
        'web',
    ];

    protected $casts = [
        'redes_sociales' => 'array',
    ];

    // Relación con el usuario (uno a uno inverso)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
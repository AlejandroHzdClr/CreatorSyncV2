<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seguidor extends Model
{
    use HasFactory;

    protected $table = 'seguidores';

    protected $fillable = [
        'usuario_id',
        'seguido_id',
        'fecha',
    ];

    // Relación con el usuario que sigue
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Relación con el usuario seguido
    public function seguido()
    {
        return $this->belongsTo(Usuario::class, 'seguido_id');
    }
}
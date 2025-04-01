<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicacion extends Model {
    use HasFactory;

    protected $table = 'publicaciones';

    protected $fillable = ['usuario_id', 'titulo', 'contenido', 'imagen'];

    public function usuario() {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}

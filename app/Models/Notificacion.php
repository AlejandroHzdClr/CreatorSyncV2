<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;
    protected $table = 'notificaciones';

    protected $fillable = ['usuario_id', 'mensaje', 'tipo','leida'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    const TIPO_LIKE = 1;
    const TIPO_COMENTARIO = 2;
    const TIPO_SEGUIDOR = 3;
}
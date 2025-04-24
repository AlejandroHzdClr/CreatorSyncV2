<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfNotificacion extends Model
{
    use HasFactory;

    protected $table = 'conf_notificacion';

    protected $fillable = [
        'user_id',
        'likes',
        'seguidores',
        'comentarios',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id');
    }
}
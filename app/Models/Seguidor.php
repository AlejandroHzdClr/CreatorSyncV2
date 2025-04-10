<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seguidor extends Model
{
    use HasFactory;

    protected $table = 'seguidores';

    protected $fillable = ['usuario_id', 'seguido_id'];

    // Relación con el modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function seguido()
    {
        return $this->belongsTo(User::class, 'seguido_id');
    }
}
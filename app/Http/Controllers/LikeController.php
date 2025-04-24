<?php
namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notificacion;

class LikeController extends Controller
{
    public function like($id)
    {
        $usuario = Auth::user();
        $publicacion = Publicacion::findOrFail($id);

        // Verificar si el usuario ya dio like
        if (!Like::where('usuario_id', $usuario->id)->where('publicacion_id', $id)->exists()) {
            // Crear el like
            Like::create([
                'usuario_id' => $usuario->id,
                'publicacion_id' => $id,
            ]);

            // Verificar si el usuario que recibe la notificación tiene habilitados los likes
            $configNotificacion = $publicacion->usuario->confNotificacion;
            if ($configNotificacion && $configNotificacion->likes) {
                // Crear la notificación
                Notificacion::create([
                    'usuario_id' => $publicacion->usuario_id,
                    'mensaje' => "{$usuario->nombre} le dio like a tu publicación: {$publicacion->titulo}",
                    'tipo' => Notificacion::TIPO_LIKE,
                ]);
            }
        }

        // Obtener el nuevo contador de likes
        $likeCount = $publicacion->likes->count();

        return response()->json(['like_count' => $likeCount]);
    }

    public function unlike($id)
    {
        $usuario = Auth::user();

        // Eliminar el like
        Like::where('usuario_id', $usuario->id)->where('publicacion_id', $id)->delete();

        // Obtener el nuevo contador de likes
        $likeCount = Publicacion::find($id)->likes->count();

        return response()->json(['like_count' => $likeCount]);
    }
}
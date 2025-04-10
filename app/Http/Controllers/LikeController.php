<?php
namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like($id)
    {
        $usuario = Auth::user();

        // Verificar si ya dio like
        if (!Like::where('usuario_id', $usuario->id)->where('publicacion_id', $id)->exists()) {
            // Crear el like
            Like::create([
                'usuario_id' => $usuario->id,
                'publicacion_id' => $id,
            ]);
        }

        // Obtener el nuevo contador de likes
        $likeCount = Publicacion::find($id)->likes->count();

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
<?php
namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notificacion;

class ComentarioController extends Controller
{

    public function store(Request $request, $id)
    {
        $request->validate([
            'contenido' => 'required|string|max:500',
        ]);

        $comentario = Comentario::create([
            'usuario_id' => auth()->id(),
            'publicacion_id' => $id,
            'contenido' => $request->contenido,
        ]);

        // Crear la notificación
        $publicacion = Publicacion::findOrFail($id);
        Notificacion::create([
            'usuario_id' => $publicacion->usuario_id,
            'mensaje' => auth()->user()->nombre . " comentó en tu publicación: {$publicacion->titulo}",
        ]);

        return response()->json($comentario);
    }

    public function index($id)
    {
        $comentarios = Comentario::where('publicacion_id', $id)->with('usuario')->get();

        return response()->json($comentarios);
    }
}
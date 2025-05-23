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

        // Crear la notificación solo si el usuario tiene habilitados los comentarios
        $publicacion = Publicacion::findOrFail($id);
        $configNotificacion = $publicacion->usuario->confNotificacion;

        if ($configNotificacion && $configNotificacion->comentarios) {
            Notificacion::create([
                'usuario_id' => $publicacion->usuario_id,
                'mensaje' => auth()->user()->nombre . " comentó en tu publicación: {$publicacion->titulo}",
                'tipo' => Notificacion::TIPO_COMENTARIO,
            ]);
        }

        return response()->json([
            'id' => $comentario->id,
            'contenido' => $comentario->contenido,
            'usuario_id' => auth()->id(),
            'usuario_nombre' => auth()->user()->nombre,
            'usuario_avatar' => auth()->user()->avatar 
                ? asset('storage/' . auth()->user()->avatar) 
                : asset('images/PerfilPredeterminado.jpg'),
            'puede_eliminar' => auth()->id() === $comentario->usuario_id || auth()->user()->rol === 'admin',
        ]);
    }

    public function index($id)
    {
        $comentarios = Comentario::where('publicacion_id', $id)->with('usuario')->get();

        return response()->json($comentarios);
    }
}
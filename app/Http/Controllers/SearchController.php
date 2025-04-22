<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publicacion;
use App\Models\Usuario;
use App\Models\Notificacion;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function buscar(Request $request)
    {
        $termino = $request->input('termino');

        // Buscar publicaciones y usuarios
        $publicaciones = Publicacion::where('titulo', 'like', '%' . $termino . '%')
            ->orWhere('contenido', 'like', '%' . $termino . '%')
            ->get();

        $usuarios = Usuario::where('nombre', 'like', '%' . $termino . '%')
            ->orWhere('email', 'like', '%' . $termino . '%')
            ->withCount('seguidores')
            ->get();

        // Obtener notificaciones
        $notificacionesNoLeidas = Notificacion::where('usuario_id', Auth::id())
            ->where('leida', false)
            ->count();

        // Pasar los datos a la vista
        return view('buscar.resultados', compact('publicaciones', 'usuarios', 'termino', 'notificacionesNoLeidas'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Models\Notificacion;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function buscar(Request $request)
    {
        $termino = $request->input('termino');

        $publicaciones = Publicacion::where('titulo', 'like', '%' . $termino . '%')
                             ->orWhere('contenido', 'like', '%' . $termino . '%')
                             ->get();

        $usuarios = Usuario::where('nombre', 'like', '%' . $termino . '%')
                         ->orWhere('email', 'like', '%' . $termino . '%')
                         ->get();

        $notificacionesNoLeidas = Notificacion::where('usuario_id', Auth::id())
                         ->where('leida', false)
                         ->count();

        return view('buscar.resultados-busqueda', compact('publicaciones', 'usuarios', 'termino', 'notificacionesNoLeidas'));
    }

    public function buscarAjax(Request $request)
    {
        $termino = $request->input('termino');

        $publicaciones = Publicacion::where('titulo', 'like', '%' . $termino . '%')
                             ->orWhere('contenido', 'like', '%' . $termino . '%')
                             ->limit(5)
                             ->get();

        $usuarios = Usuario::where('nombre', 'like', '%' . $termino . '%')
                         ->orWhere('email', 'like', '%' . $termino . '%')
                         ->limit(5)
                         ->get();

        return response()->json(['publicaciones' => $publicaciones, 'usuarios' => $usuarios]);
    }
}
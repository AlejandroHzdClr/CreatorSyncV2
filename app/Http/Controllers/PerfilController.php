<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Models\Notificacion;
use Illuminate\Support\Facades\Auth;


class PerfilController extends Controller
{
    public function show($id)
    {
        $usuario = Usuario::with(['perfil', 'seguidores', 'siguiendo'])
            ->withCount('seguidores') // Cuenta los seguidores
            ->findOrFail($id);

        $notificacionesNoLeidas = Notificacion::where('usuario_id', Auth::id())
            ->where('leida', false)
            ->count();

        return view('inicio.perfil', compact('usuario', 'notificacionesNoLeidas'));
    }
}
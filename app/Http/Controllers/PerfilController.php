<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function show($id)
    {
        $usuario = Usuario::with(['perfil', 'seguidores', 'siguiendo'])
            ->withCount('seguidores') // Cuenta los seguidores
            ->findOrFail($id);

        return view('inicio.perfil', compact('usuario'));
    }
}
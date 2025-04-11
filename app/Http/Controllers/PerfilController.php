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

        // Obtener el último post del usuario
        $ultimoPost = $usuario->publicaciones()->latest()->first();

        return view('inicio.perfil', compact('usuario', 'notificacionesNoLeidas', 'ultimoPost'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Auth::user();

        // Validar los datos
        $request->validate([
            'descripcion' => 'nullable|string|max:2500',
            'redes' => 'nullable|array',
            'redes.*.nombre' => 'required|string|max:50',
            'redes.*.url' => 'nullable|url',
        ]);

        // Actualizar la descripción del usuario
        $usuario->descripcion = $request->descripcion;
        $usuario->save();

        // Actualizar las redes sociales en el perfil
        if ($usuario->perfil) {
            $usuario->perfil->redes_sociales = $request->redes; // Guardar directamente como array
            $usuario->perfil->save();
        } else {
            $usuario->perfil()->create([
                'redes_sociales' => $request->redes,
            ]);
        }

        return redirect()->route('perfil.show', $usuario->id)->with('success', 'Perfil actualizado correctamente.');
    }
}
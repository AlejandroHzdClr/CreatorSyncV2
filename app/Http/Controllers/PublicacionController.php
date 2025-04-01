<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publicacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PublicacionController extends Controller {
    public function index() {
        // Obtener publicaciones con información del usuario
        $publicaciones = Publicacion::with('usuario')->orderBy('created_at', 'desc')->get();
        
        return view('inicio.index', compact('publicaciones'));
    }

    // Subir publicación
    public function store(Request $request) {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $usuario_id = Auth::id(); // Obtiene el ID del usuario autenticado

        // Guardar imagen si existe
        $rutaImagen = $request->file('imagen') ? $request->file('imagen')->store('uploads', 'public') : null;

        $publicacion = Publicacion::create([
            'usuario_id' => $usuario_id,
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'imagen' => $rutaImagen ? asset("storage/$rutaImagen") : null,
            
        ]);

        return response()->json(['success' => 'Publicación subida correctamente', 'data' => $publicacion]);
    }
}

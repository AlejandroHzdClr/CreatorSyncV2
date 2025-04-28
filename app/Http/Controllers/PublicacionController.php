<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publicacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Notificacion;


class PublicacionController extends Controller {
    public function index() {
        // Obtener publicaciones con información del usuario
        $publicaciones = Publicacion::with('usuario')->orderBy('created_at', 'desc')->get();
        
        // Obtener notificaciones
        $notificacionesNoLeidas = Notificacion::where('usuario_id', Auth::id())
            ->where('leida', false)
            ->count();
    
        // Obtener el avatar del usuario autenticado
        $avatar = Auth::user()->avatar;
    
        return view('inicio.index', compact('publicaciones', 'notificacionesNoLeidas', 'avatar'));
    }

    // Subir publicación
    public function store(Request $request) {
        $request->validate([
            'titulo' => 'nullable|string|max:255',
            'contenido' => 'nullable|string',
            'imagen' => 'nullable|image|max:2048',
        ]);

        // Verificar que al menos uno de los campos esté presente
        if (!$request->titulo && !$request->contenido && !$request->hasFile('imagen')) {
            return redirect()->back()->with('error', 'Debes proporcionar al menos un título, contenido o una imagen.');
        }

        $usuario_id = Auth::id(); // Obtiene el ID del usuario autenticado

        // Guardar imagen si existe
        $rutaImagen = $request->file('imagen') ? $request->file('imagen')->store('uploads', 'public') : null;

        $publicacion = Publicacion::create([
            'usuario_id' => $usuario_id,
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'imagen' => $rutaImagen,
        ]);

        // Redirigir a index con un mensaje de éxito
        return redirect()->route('inicio.index')->with('success', 'Publicación subida correctamente.');
    }

    // Elegir una publicación
    public function show($id)
    {
        // Buscar la publicación por ID
        $publicacion = Publicacion::with('usuario')->findOrFail($id);

        // Retornar la vista con los detalles de la publicación
        return view('posts.show', compact('publicacion'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $publicacion = Publicacion::findOrFail($id);

        // Verificar si el usuario es el propietario o un administrador
        if (Auth::id() !== $publicacion->usuario_id && Auth::user()->rol !== 'admin') {
            return redirect()->back()->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $publicacion->titulo = $request->titulo;
        $publicacion->contenido = $request->contenido;

        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior si existe
            if ($publicacion->imagen) {
                Storage::delete($publicacion->imagen);
            }

            // Guardar la nueva imagen
            $publicacion->imagen = $request->file('imagen')->store('publicaciones');
        }

        $publicacion->save();

        return redirect()->back()->with('success', 'Publicación actualizada correctamente.');
    }
}

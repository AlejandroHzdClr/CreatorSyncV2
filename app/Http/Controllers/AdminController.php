<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Publicacion;
use Illuminate\Http\Request;
use App\Models\Notificacion;
use App\Models\Like;
use App\Models\Comentario;
use App\Models\Seguidor;

class AdminController extends Controller
{
    public function index()
    {
        // Verificar si el usuario es administrador
        if (Auth::user()->rol !== 'admin') {
            return redirect('/')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        $notificacionesNoLeidas = Notificacion::where('usuario_id', Auth::id())
            ->where('leida', false)
            ->count();

        $usuarios = Usuario::with('perfil')->get();
        $publicaciones = Publicacion::all();

        return view('admin.index', compact('usuarios', 'publicaciones', 'notificacionesNoLeidas'));
    }

    public function eliminarUsuario($id)
    {
        if (Auth::user()->rol !== 'admin') {
            return redirect('/')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return redirect()->route('admin.index')->with('success', 'Usuario eliminado correctamente.');
    }

    public function eliminarPublicacion($id)
    {
        $publicacion = Publicacion::findOrFail($id);

        // Verificar si el usuario es el propietario o un administrador
        if (Auth::id() !== $publicacion->usuario_id && Auth::user()->rol !== 'admin') {
            return redirect('/')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $publicacion->delete();

        return redirect()->back()->with('success', 'Publicación eliminada correctamente.');
    }

    public function eliminarComentario($id)
    {
        $comentario = Comentario::findOrFail($id);

        // Verificar si el usuario es el propietario del comentario, de la publicación asociada, o un administrador
        if (Auth::id() !== $comentario->usuario_id && Auth::id() !== $comentario->publicacion->usuario_id && Auth::user()->rol !== 'admin') {
            return redirect('/')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $comentario->delete();

        return redirect()->back()->with('success', 'Comentario eliminado correctamente.');
    }

    public function editarUsuario($id)
    {
        if (Auth::user()->rol !== 'admin') {
            return redirect('/')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $notificacionesNoLeidas = Notificacion::where('usuario_id', Auth::id())
            ->where('leida', false)
            ->count();

        $usuario = Usuario::findOrFail($id);
        return view('admin.editar_usuario', compact('usuario', 'notificacionesNoLeidas'));
    }

    public function actualizarUsuario(Request $request, $id)
    {
        if (Auth::user()->rol !== 'admin') {
            return redirect('/')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        // Validar los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rol' => 'required|in:admin,usuario',
            'descripcion' => 'nullable|string|max:2500',
            'web' => 'nullable|url|max:255',
            'redes_sociales' => 'nullable|array',
            'redes_sociales.*.nombre' => 'required|string|max:50',
            'redes_sociales.*.url' => 'nullable|url|max:255',
        ]);

        // Actualizar datos del usuario
        $usuario = Usuario::findOrFail($id);
        $usuario->update($request->only(['nombre', 'email', 'rol']));

        // Actualizar datos del perfil
        if ($usuario->perfil) {
            $usuario->perfil->update([
                'contenido_favorito' => $request->input('descripcion'),
                'web' => $request->input('web'),
                'redes_sociales' => $request->input('redes_sociales'),
            ]);
        } else {
            $usuario->perfil()->create([
                'contenido_favorito' => $request->input('descripcion'),
                'web' => $request->input('web'),
                'redes_sociales' => $request->input('redes_sociales'),
            ]);
        }

        return redirect()->route('admin.index')->with('success', 'Usuario actualizado correctamente.');
    }
}
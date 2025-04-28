<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Notificacion;
use App\Models\ConfNotificacion;


class ConfiguracionController extends Controller
{
    public function index()
    {
        // Obtén el usuario autenticado
        $usuario = Auth::user();

        $notificacionesNoLeidas = Notificacion::where('usuario_id', Auth::id())
            ->where('leida', false)
            ->count();

        // Retorna la vista con los datos del usuario
        return view('configuracion.index', compact('usuario', 'notificacionesNoLeidas'));
    }

    public function updatePerfil(Request $request)
    {
        $usuario = Auth::user();

        // Validar solo los campos que están presentes en la solicitud
        $request->validate([
            'nombre' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:usuarios,email,' . $usuario->id,
            'foto' => 'nullable|image|max:2048',
        ]);

        // Actualizar el nombre si está presente
        if ($request->filled('nombre')) {
            $usuario->nombre = $request->nombre;
        }

        // Actualizar el email si está presente
        if ($request->filled('email')) {
            $usuario->email = $request->email;
        }

        // Manejar la subida de la foto si está presente
        if ($request->hasFile('foto')) {
            // Eliminar la imagen anterior si existe
            if ($usuario->avatar) {
                \Storage::disk('public')->delete($usuario->avatar);
            }

            // Guardar la nueva imagen
            $rutaImagen = $request->file('foto')->store('uploads', 'public');
            $usuario->avatar = $rutaImagen; // Guardar solo la ruta relativa
        }

        // Guardar los cambios en el usuario
        $usuario->save();

        return redirect()->route('configuracion.index')->with('success', 'Perfil actualizado correctamente.');
    }

    public function updateSeguridad(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'password' => 'required|string',
            'newPassword' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->password, $usuario->password)) {
            return redirect()->route('configuracion.index')->withErrors(['error' => 'La contraseña actual no es correcta.']);
        }

        $usuario->password = Hash::make($request->newPassword);
        $usuario->save();

        return redirect()->route('configuracion.index')->with('success', 'Perfil actualizado correctamente.');    
    }

    public function updateNotificaciones(Request $request)
    {
        $usuario = Auth::user();

        // Asegúrate de que el usuario tenga una configuración de notificaciones
        if (!$usuario->confNotificacion) {
            $usuario->confNotificacion()->create([
                'likes' => 1,
                'seguidores' => 1,
                'comentarios' => 1,
            ]);
        }

        // Convierte los valores de los checkboxes a 1 o 0
        $usuario->confNotificacion->update([
            'likes' => $request->has('likes') ? 1 : 0,
            'seguidores' => $request->has('seguidores') ? 1 : 0,
            'comentarios' => $request->has('comentarios') ? 1 : 0,
        ]);

        return redirect()->route('configuracion.index')->with('success', 'Configuración de notificaciones actualizada correctamente.');
    }
}
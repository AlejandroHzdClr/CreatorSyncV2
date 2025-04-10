<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Notificacion;


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
                $rutaAnterior = str_replace(asset('storage/'), '', $usuario->avatar);
                \Storage::disk('public')->delete($rutaAnterior);
            }

            // Guardar la nueva imagen
            $rutaImagen = $request->file('foto')->store('avatars', 'public');
            $usuario->avatar = asset("storage/$rutaImagen");
        }

        // Guardar los cambios en el usuario
        $usuario->save();

        return redirect()->route('configuracion.index')->with('success', 'Perfil actualizado correctamente.');    }

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

        return redirect()->route('configuracion.index')->with('success', 'Perfil actualizado correctamente.');    }

    public function updateNotificaciones(Request $request)
    {
        $usuario = Auth::user();

        // Aquí puedes guardar las preferencias de notificaciones en la base de datos
        // Por ejemplo:
        // $usuario->notificaciones = json_encode($request->all());
        // $usuario->save();

        return response()->json(['success' => 'Preferencias de notificaciones actualizadas.']);
    }
}
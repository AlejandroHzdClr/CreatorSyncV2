<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ConfiguracionController extends Controller
{
    public function index()
    {
        // Obtén el usuario autenticado
        $usuario = Auth::user();

        // Retorna la vista con los datos del usuario
        return view('configuracion.index', compact('usuario'));
    }

    public function updatePerfil(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usuarios,email,' . $usuario->id,
            'foto' => 'nullable|image|max:2048',
        ]);

        $usuario->nombre = $request->nombre;
        $usuario->email = $request->email;

        if ($request->hasFile('foto')) {
            $rutaImagen = $request->file('foto')->store('avatars', 'public');
            $usuario->avatar = asset("storage/$rutaImagen");
        }

        $usuario->save();

        return response()->json(['success' => 'Perfil actualizado correctamente.']);
    }

    public function updateSeguridad(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'password' => 'required|string',
            'newPassword' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->password, $usuario->password)) {
            return response()->json(['error' => 'La contraseña actual no es correcta.'], 400);
        }

        $usuario->password = Hash::make($request->newPassword);
        $usuario->save();

        return response()->json(['success' => 'Contraseña actualizada correctamente.']);
    }

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
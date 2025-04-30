<?php

namespace App\Http\Controllers\Auth;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\ConfNotificacion;

class RegisterController extends Controller
{
    // Muestra el formulario de registro (si estás usando vistas)
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Maneja la lógica del registro
    public function register(Request $request)
    {
        // Valida la solicitud
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:usuarios,nombre',
            'email' => 'required|string|email|max:100|unique:usuarios,email',
            'password' => 'required|string|min:8|confirmed', // Requiere confirmación de la password
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Crea el usuario
        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => bcrypt($request->password),  // Encriptamos la password
            'rol' => 'usuario',  // El rol predeterminado, puedes modificarlo
            'estado' => 'activo',  // El estado predeterminado
        ]);

        ConfNotificacion::create([
            'user_id' => $usuario->id,
            'likes' => 1,
            'seguidores' => 1,
            'comentarios' => 1,
        ]);

        // Iniciar sesión automáticamente
        auth()->login($usuario);

        // Redirigir al usuario después del registro
        return redirect()->route('inicio.index')->with('success', 'Registro exitoso. Bienvenido!');
    }
}

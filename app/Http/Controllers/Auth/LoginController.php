<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'nombre' => 'required|string',  // Usamos 'nombre' en lugar de 'email'
            'password' => 'required|string',
        ]);

        // Obtener el usuario por su nombre
        $usuario = Usuario::where('nombre', $request->nombre)->first();

        // Verificar si existe el usuario y si la contraseña es correcta
        if ($usuario && Hash::check($request->password, $usuario->password)) {
            // Autenticación exitosa, iniciar sesión
            Auth::login($usuario);
            return redirect()->intended('/dashboard');
        } else {
            // Autenticación fallida
            return back()->withErrors(['error' => 'Credenciales incorrectas.']);
        }
    }

    // Método para logout
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}

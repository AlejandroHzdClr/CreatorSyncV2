<?php
namespace App\Http\Controllers;

use App\Models\Seguidor;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeguidorController extends Controller
{
    public function seguir($id)
    {
        $usuario = Auth::user();

        // Verificar si ya sigue al usuario
        if (Seguidor::where('usuario_id', $usuario->id)->where('seguido_id', $id)->exists()) {
            return redirect()->back()->with('message', 'Ya sigues a este usuario.');
        }

        // Crear el registro de seguimiento
        Seguidor::create([
            'usuario_id' => $usuario->id,
            'seguido_id' => $id,
        ]);

        return redirect()->back()->with('message', 'Has comenzado a seguir a este usuario.');
    }

    public function dejarDeSeguir($id)
    {
        $usuario = Auth::user();

        // Eliminar el registro de seguimiento
        Seguidor::where('usuario_id', $usuario->id)->where('seguido_id', $id)->delete();

        return redirect()->back()->with('message', 'Has dejado de seguir a este usuario.');
    }
}
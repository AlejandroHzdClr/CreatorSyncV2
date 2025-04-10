<?php
namespace App\Http\Controllers;

use App\Models\Seguidor;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notificacion;

class SeguidorController extends Controller
{
    public function seguir($id)
    {
        $usuario = Auth::user();

        if (!$usuario->siguiendo->contains('seguido_id', $id)) {
            $usuario->siguiendo()->create(['seguido_id' => $id]);

            // Crear la notificación
            $seguido = Usuario::findOrFail($id);
            Notificacion::create([
                'usuario_id' => $id,
                'mensaje' => "{$usuario->nombre} comenzó a seguirte.",
            ]);
        }

        $seguidoresCount = Usuario::find($id)->seguidores->count();

        return response()->json(['seguidores_count' => $seguidoresCount]);
    }

    public function dejarDeSeguir($id)
    {
        $usuario = Auth::user();

        $usuario->siguiendo()->where('seguido_id', $id)->delete();

        $seguidoresCount = Usuario::find($id)->seguidores->count();

        return response()->json(['seguidores_count' => $seguidoresCount]);
    }
}
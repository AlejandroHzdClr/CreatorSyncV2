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

            // Crear la notificación solo si el usuario tiene habilitados los seguidores
            $seguido = Usuario::findOrFail($id);
            $configNotificacion = $seguido->confNotificacion;

            if ($configNotificacion && $configNotificacion->seguidores) {
                Notificacion::create([
                    'usuario_id' => $id,
                    'mensaje' => "{$usuario->nombre} comenzó a seguirte.",
                    'tipo' => Notificacion::TIPO_SEGUIDOR,
                ]);
            }
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
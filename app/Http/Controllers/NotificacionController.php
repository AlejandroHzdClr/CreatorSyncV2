<?php
namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Support\Facades\Auth;


class NotificacionController extends Controller
{
    public function index()
    {
        $notificaciones = Notificacion::where('usuario_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(6); // Mostrar 6 notificaciones por página

        $notificacionesNoLeidas = Notificacion::where('usuario_id', Auth::id())
            ->where('leida', false)
            ->count();

        return view('notificaciones.index', compact('notificaciones', 'notificacionesNoLeidas'));
    }

    public function marcarLeida($id)
    {
        $notificacion = Notificacion::where('id', $id)
            ->where('usuario_id', auth()->id())
            ->firstOrFail();

        $notificacion->update(['leida' => true]);

        return response()->json(['success' => true]);
    }

    public function eliminar($id)
    {
        $notificacion = Notificacion::where('id', $id)
            ->where('usuario_id', auth()->id())
            ->firstOrFail();

        $notificacion->delete();

        return response()->json(['success' => true]);
    }

    public function eliminarTodas()
    {
        Notificacion::where('usuario_id', auth()->id())->delete();

        return redirect()->back()->with('success', 'Todas las notificaciones eliminadas.');
    }
    public function marcarTodasLeidas()
    {
        Notificacion::where('usuario_id', auth()->id())
            ->where('leida', false)
            ->update(['leida' => true]);

        return redirect()->back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }
}
<?php
namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'contenido' => 'required|string|max:500',
        ]);

        Comentario::create([
            'usuario_id' => Auth::id(),
            'publicacion_id' => $id,
            'contenido' => $request->contenido,
        ]);

        return response()->json(['message' => 'Comentario agregado correctamente.']);
    }

    public function index($id)
    {
        $comentarios = Comentario::where('publicacion_id', $id)->with('usuario')->get();

        return response()->json($comentarios);
    }
}
<?php

namespace Tests\Feature;

use App\Models\Usuario;
use App\Models\Publicacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComentarEnPublicacionTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_comentar_publicacion()
    {
        $usuario = Usuario::factory()->create();
        $publicacion = Publicacion::factory()->create();

        $this->actingAs($usuario);

        $response = $this->postJson("/comentarios/{$publicacion->id}", [
            'contenido' => 'Este es un comentario.',
        ]);

        // Verifica que el estado HTTP sea 200
        $response->assertStatus(200);

        // Verifica que el JSON devuelto contenga los datos esperados
        $response->assertJson([
            'contenido' => 'Este es un comentario.',
            'usuario_nombre' => $usuario->nombre,
        ]);

        // Verifica que el comentario se haya guardado en la base de datos
        $this->assertDatabaseHas('comentarios', [
            'contenido' => 'Este es un comentario.',
            'usuario_id' => $usuario->id,
            'publicacion_id' => $publicacion->id,
        ]);
    }
}
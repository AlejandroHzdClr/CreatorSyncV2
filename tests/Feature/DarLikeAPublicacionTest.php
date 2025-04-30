<?php

namespace Tests\Feature;

use App\Models\Usuario;
use App\Models\Publicacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DarLikeAPublicacionTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_dar_like_a_publicacion()
    {
        $usuario = Usuario::factory()->create();
        $publicacion = Publicacion::factory()->create();

        $this->actingAs($usuario);

        $response = $this->post("/like/{$publicacion->id}");

        // Verifica que el estado HTTP sea 200
        $response->assertStatus(200);

        // Verifica que el like se haya guardado en la base de datos
        $this->assertDatabaseHas('likes', [
            'usuario_id' => $usuario->id,
            'publicacion_id' => $publicacion->id,
        ]);
    }

    public function test_usuario_puede_quitar_like_a_publicacion()
    {
        $usuario = Usuario::factory()->create();
        $publicacion = Publicacion::factory()->create();

        // El usuario da like a la publicación
        $publicacion->likes()->create([
            'usuario_id' => $usuario->id,
        ]);

        $this->actingAs($usuario);

        $response = $this->post("/unlike/{$publicacion->id}");

        // Verifica que el estado HTTP sea 200
        $response->assertStatus(200);

        // Verifica que el like se haya eliminado de la base de datos
        $this->assertDatabaseMissing('likes', [
            'usuario_id' => $usuario->id,
            'publicacion_id' => $publicacion->id,
        ]);
    }

    public function test_usuario_no_autenticado_no_puede_dar_like_a_publicacion()
    {
        $publicacion = Publicacion::factory()->create();

        $response = $this->post("/like/{$publicacion->id}");

        // Verifica que el usuario no autenticado sea redirigido al login
        $response->assertRedirect('/login');

        // Verifica que no se haya guardado el like en la base de datos
        $this->assertDatabaseMissing('likes', [
            'publicacion_id' => $publicacion->id,
        ]);
    }
}

    
    
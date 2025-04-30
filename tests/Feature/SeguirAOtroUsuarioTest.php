<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Seguidor;

class SeguirAOtroUsuarioTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_seguir_a_otro_usuario()
    {
        $usuario = Usuario::factory()->create();
        $otroUsuario = Usuario::factory()->create();

        $this->actingAs($usuario);

        $response = $this->post("/seguir/{$otroUsuario->id}");

        // Verifica que el estado HTTP sea 200
        $response->assertStatus(200);

        // Verifica que la relación de seguimiento se haya guardado en la base de datos
        $this->assertDatabaseHas('seguidores', [
            'usuario_id' => $usuario->id,
            'seguido_id' => $otroUsuario->id,
        ]);
    }

    public function test_usuario_puede_dejar_de_seguir_a_otro_usuario()
    {
        $usuario = Usuario::factory()->create();
        $otroUsuario = Usuario::factory()->create();

        // El usuario sigue a otro usuario
        \App\Models\Seguidor::create([
            'usuario_id' => $usuario->id,
            'seguido_id' => $otroUsuario->id,
        ]);

        $this->actingAs($usuario);

        $response = $this->post("/dejar-de-seguir/{$otroUsuario->id}");

        // Verifica que el estado HTTP sea 200
        $response->assertStatus(200);

        // Verifica que la relación de seguimiento se haya eliminado de la base de datos
        $this->assertDatabaseMissing('seguidores', [
            'usuario_id' => $usuario->id,
            'seguido_id' => $otroUsuario->id,
        ]);
    }

    public function test_usuario_no_autenticado_no_puede_seguir_a_otro_usuario()
    {
        $otroUsuario = Usuario::factory()->create();

        $response = $this->post("/seguir/{$otroUsuario->id}");

        // Verifica que el usuario no autenticado sea redirigido al login
        $response->assertRedirect('/login');

        // Verifica que no se haya guardado la relación de seguimiento en la base de datos
        $this->assertDatabaseMissing('seguidores', [
            'seguido_id' => $otroUsuario->id,
        ]);
    }
}
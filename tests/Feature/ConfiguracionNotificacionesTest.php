<?php
namespace Tests\Feature;

use App\Models\ConfNotificacion;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfiguracionNotificacionesTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_actualizar_configuracion_de_notificaciones()
    {
        $usuario = Usuario::factory()->create();
        $usuario->confNotificacion()->create([
            'likes' => true,
            'seguidores' => true,
            'comentarios' => true,
        ]);

        $this->actingAs($usuario);

        $response = $this->post('/configuracion/notificaciones', [
            'seguidores' => "on", // Solo enviar las casillas marcadas
        ]);

        $response->assertRedirect('/configuracion');

        $this->assertDatabaseHas('conf_notificacion', [
            'user_id' => $usuario->id,
            'likes' => 0, // Likes debe ser 0 porque no se envió
            'seguidores' => 1, // Seguidores debe ser 1 porque se envió
            'comentarios' => 0, // Comentarios debe ser 0 porque no se envió
        ]);
    }
}
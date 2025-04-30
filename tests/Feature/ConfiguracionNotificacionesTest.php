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
            'likes' => null,
            'seguidores' => "on",
            'comentarios' => null,
        ]);

        $response->assertRedirect('/configuracion');

        $this->assertDatabaseHas('conf_notificacion', [
            'user_id' => $usuario->id,
            'likes' => 0,
            'seguidores' => 1,
            'comentarios' => 0,
        ]);
    }
}
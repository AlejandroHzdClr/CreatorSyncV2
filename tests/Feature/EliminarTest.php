<?php
namespace Tests\Feature;

use App\Models\Comentario;
use App\Models\Publicacion;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EliminarTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_puede_eliminar_publicacion()
    {
        $admin = Usuario::factory()->create(['rol' => 'admin']);
        $publicacion = Publicacion::factory()->create();

        $this->actingAs($admin);

        $response = $this->delete("/admin/publicaciones/{$publicacion->id}");

        $response->assertRedirect('/');
        $this->assertDatabaseMissing('publicaciones', ['id' => $publicacion->id]);
    }

    public function test_propietario_puede_eliminar_su_publicacion()
    {
        $usuario = Usuario::factory()->create();
        $publicacion = Publicacion::factory()->create(['usuario_id' => $usuario->id]);

        $this->actingAs($usuario);

        $response = $this->delete("/admin/publicaciones/{$publicacion->id}");

        $response->assertRedirect('/');
        $this->assertDatabaseMissing('publicaciones', ['id' => $publicacion->id]);
    }
}
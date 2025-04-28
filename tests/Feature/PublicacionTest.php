<?php
namespace Tests\Feature;

use App\Models\Publicacion;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicacionTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_crear_publicacion()
    {
        $usuario = Usuario::factory()->create();

        $this->actingAs($usuario);

        $response = $this->post('/publicaciones', [
            'titulo' => 'Mi primera publicación',
            'contenido' => 'Este es el contenido de mi publicación.',
        ]);

        $response->assertRedirect('/inicio');
        $this->assertDatabaseHas('publicaciones', [
            'titulo' => 'Mi primera publicación',
            'contenido' => 'Este es el contenido de mi publicación.',
        ]);
    }

    public function test_usuario_no_autenticado_no_puede_crear_publicacion()
    {
        $response = $this->post('/publicaciones', [
            'titulo' => 'Mi primera publicación',
            'contenido' => 'Este es el contenido de mi publicación.',
        ]);

        $response->assertRedirect('/login');
    }
}
<?php

namespace Tests\Feature;

use App\Models\Usuario;
use App\Models\Publicacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuscarTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_buscar_usuarios()
    {
        // Crea usuarios en la base de datos
        $usuario1 = Usuario::factory()->create(['nombre' => 'Juan Pérez']);
        $usuario2 = Usuario::factory()->create(['nombre' => 'María López']);

        // Actúa como un usuario autenticado
        $this->actingAs($usuario1);

        // Realiza una búsqueda de usuarios
        $response = $this->get('/buscar?termino=Juan');

        // Verifica que el estado HTTP sea 200
        $response->assertStatus(200);

        // Verifica que la vista contenga los usuarios esperados
        $response->assertViewIs('buscar.resultados');
        $response->assertViewHas('usuarios', function ($usuarios) {
            return $usuarios->contains('nombre', 'Juan Pérez');
        });

        // Verifica que no haya publicaciones en los resultados
        $response->assertViewHas('publicaciones', function ($publicaciones) {
            return $publicaciones->isEmpty();
        });
    }

    public function test_usuario_puede_buscar_publicaciones()
    {
        // Crea publicaciones en la base de datos
        $publicacion = Publicacion::factory()->create(['titulo' => 'Mi primera publicación']);
        $usuario = Usuario::factory()->create(['nombre' => 'Juan Pérez']);

        // Actúa como un usuario autenticado
        $this->actingAs($usuario);

        // Realiza una búsqueda de publicaciones
        $response = $this->get('/buscar?termino=primera');

        // Verifica que el estado HTTP sea 200
        $response->assertStatus(200);

        // Verifica que la vista contenga las publicaciones esperadas
        $response->assertViewIs('buscar.resultados');
        $response->assertViewHas('publicaciones', function ($publicaciones) {
            return $publicaciones->contains('titulo', 'Mi primera publicación');
        });

        // Verifica que no haya usuarios en los resultados
        $response->assertViewHas('usuarios', function ($usuarios) {
            return $usuarios->isEmpty();
        });
    }
}
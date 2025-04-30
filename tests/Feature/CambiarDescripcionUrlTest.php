<?php

namespace Tests\Feature;

use App\Models\Usuario;
use App\Models\Perfil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CambiarDescripcionUrlTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_cambiar_descripcion_y_añadir_redes_sociales()
    {
        // Crea un usuario con un perfil asociado
        $usuario = Usuario::factory()->create([
            'descripcion' => 'Descripción original',
        ]);

        $usuario->perfil()->create([
            'redes_sociales' => null,
        ]);

        $this->actingAs($usuario);

        // Realiza la solicitud para actualizar la descripción y añadir redes sociales
        $response = $this->put(route('perfil.update', $usuario->id), [
            'descripcion' => 'Nueva descripción',
            'redes' => [
                ['nombre' => 'Twitter', 'url' => 'https://twitter.com/usuario'],
                ['nombre' => 'LinkedIn', 'url' => 'https://linkedin.com/in/usuario'],
            ],
        ]);

        // Verifica que el estado HTTP sea 302 (redirección)
        $response->assertStatus(302);

        // Verifica que la descripción se haya actualizado en la base de datos
        $this->assertDatabaseHas('usuarios', [
            'id' => $usuario->id,
            'descripcion' => 'Nueva descripción',
        ]);

        // Verifica que las redes sociales se hayan actualizado en la base de datos
        $perfil = $usuario->perfil()->first();
        $redesSociales = $perfil->redes_sociales; // Ya es un array debido al cast

        $this->assertEqualsCanonicalizing([
            ['nombre' => 'Twitter', 'url' => 'https://twitter.com/usuario'],
            ['nombre' => 'LinkedIn', 'url' => 'https://linkedin.com/in/usuario'],
        ], $redesSociales);
    }
}
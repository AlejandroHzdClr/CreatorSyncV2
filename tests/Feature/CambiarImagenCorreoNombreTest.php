<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CambiarImagenCorreoNombreTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_cambiar_imagen_correo_y_nombre()
    {
        // Simula el almacenamiento de archivos
        Storage::fake('public');

        // Crea un usuario
        $usuario = Usuario::factory()->create([
            'nombre' => 'Nombre Original',
            'email' => 'correo@original.com',
        ]);

        $this->actingAs($usuario);

        // Simula la subida de una imagen
        $imagen = UploadedFile::fake()->image('avatar.jpg');

        // Realiza la solicitud para actualizar el perfil
        $response = $this->post('/configuracion/perfil', [
            'nombre' => 'Nuevo Nombre',
            'email' => 'nuevo@correo.com',
            'foto' => $imagen,
        ]);

        // Verifica que el estado HTTP sea 302 (redirección)
        $response->assertStatus(302);

        // Verifica que los datos se hayan actualizado en la base de datos
        $this->assertDatabaseHas('usuarios', [
            'id' => $usuario->id,
            'nombre' => 'Nuevo Nombre',
            'email' => 'nuevo@correo.com',
        ]);

        // Verifica que la imagen se haya almacenado en la carpeta 'uploads'
        Storage::disk('public')->assertExists('uploads/' . $imagen->hashName());
    }
}
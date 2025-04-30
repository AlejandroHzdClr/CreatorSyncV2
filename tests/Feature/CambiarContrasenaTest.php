<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CambiarContrasenaTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_cambiar_contrasena()
    {
        $usuario = Usuario::factory()->create([
            'password' => Hash::make('contraseña_actual'),
        ]);

        $this->actingAs($usuario);

        $response = $this->post('/configuracion/seguridad', [
            'password' => 'contraseña_actual',
            'newPassword' => 'nueva_contraseña',
            'newPassword_confirmation' => 'nueva_contraseña',
        ]);

        // Verifica que el estado HTTP sea 302 (redirección)
        $response->assertStatus(302);

        // Verifica que la contraseña se haya actualizado
        $this->assertTrue(Hash::check('nueva_contraseña', $usuario->fresh()->password));
    }

    public function test_usuario_no_puede_cambiar_contrasena_con_password_actual_incorrecto()
    {
        $usuario = Usuario::factory()->create([
            'password' => Hash::make('contraseña_actual'),
        ]);

        $this->actingAs($usuario);

        $response = $this->post('/configuracion/seguridad', [
            'password' => 'contraseña_incorrecta',
            'newPassword' => 'nueva_contraseña',
            'newPassword_confirmation' => 'nueva_contraseña',
        ]);

        // Verifica que el estado HTTP sea 302 (redirección)
        $response->assertStatus(302);

        // Verifica que se muestre un error en la sesión
        $response->assertSessionHasErrors(['error' => 'La contraseña actual no es correcta.']);

        // Verifica que la contraseña no se haya actualizado
        $this->assertTrue(Hash::check('contraseña_actual', $usuario->fresh()->password));
    }
}
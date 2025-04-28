<?php
namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_registrarse()
    {
        $response = $this->post('/register', [
            'nombre' => 'Nuevo Usuario',
            'email' => 'nuevo@example.com',
            'password' => 'password', // Contraseña sin hashear
            'password_confirmation' => 'password', // Confirmación de contraseña
        ]);

        $response->assertRedirect('/inicio'); // Cambia '/' por la ruta de inicio de tu aplicación

        // Verificar que el usuario se haya creado en la base de datos
        $this->assertDatabaseHas('usuarios', [
            'nombre' => 'Nuevo Usuario',
            'email' => 'nuevo@example.com',
        ]);

        // Verificar que la contraseña esté hasheada
        $usuario = Usuario::where('email', 'nuevo@example.com')->first();
        $this->assertTrue(Hash::check('password', $usuario->password));
    }


    public function test_usuario_puede_iniciar_sesion()
    {
        $usuario = Usuario::factory()->create([
            'nombre' => 'Test User',
            'password' => Hash::make('password'), // Contraseña hasheada
        ]);

        $response = $this->post('/login', [
            'nombre' => 'Test User', // Usar 'nombre' en lugar de 'email'
            'password' => 'password',
        ]);

        $response->assertRedirect('/inicio'); // Cambia '/inicio' según la redirección de tu aplicación
        $this->assertAuthenticatedAs($usuario);
    }

    public function test_usuario_no_puede_iniciar_sesion_con_credenciales_invalidas()
    {
        $usuario = Usuario::factory()->create([
            'nombre' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'), // Usar Hash::make para la contraseña
        ]);

        $response = $this->post('/login', [
            'nombre' => 'Test User',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('error'); 
        $this->assertGuest();
    }
}
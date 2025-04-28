<?php
namespace Database\Factories;

use App\Models\Publicacion;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublicacionFactory extends Factory
{
    protected $model = Publicacion::class;

    public function definition()
    {
        return [
            'titulo' => $this->faker->sentence,
            'contenido' => $this->faker->paragraph,
            'usuario_id' => Usuario::factory(), // Relación con el modelo Usuario
        ];
    }
}
<?php

namespace Database\Factories;

use App\Models\Perfil;
use Illuminate\Database\Eloquent\Factories\Factory;

class PerfilFactory extends Factory
{
    protected $model = Perfil::class;

    public function definition()
    {
        return [
            'usuario_id' => null, // Esto se asignará al crear el perfil
            'contenido_favorito' => $this->faker->sentence,
            'web' => $this->faker->url,
        ];
    }
}
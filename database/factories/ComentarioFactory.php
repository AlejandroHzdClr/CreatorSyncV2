<?php

namespace Database\Factories;

use App\Models\Comentario;
use App\Models\Publicacion;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComentarioFactory extends Factory
{
    protected $model = Comentario::class;

    public function definition()
    {
        return [
            'publicacion_id' => Publicacion::factory(),
            'usuario_id' => Usuario::factory(),
            'contenido' => $this->faker->sentence,
        ];
    }
}
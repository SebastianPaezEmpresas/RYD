<?php

namespace Database\Factories;

use App\Models\Encuesta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Encuesta>
 */
class EncuestaFactory extends Factory
{
    protected $model = Encuesta::class;

    public function definition()
    {
        return [
            'titulo' => $this->faker->sentence,
            'descripcion' => $this->faker->paragraph,
            'estado' => $this->faker->randomElement(['pendiente', 'completado']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

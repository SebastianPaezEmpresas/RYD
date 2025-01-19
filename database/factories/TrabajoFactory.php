<?php

namespace Database\Factories;

use App\Models\Trabajo;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrabajoFactory extends Factory
{
    protected $model = Trabajo::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->sentence(),
            'descripcion' => $this->faker->paragraph(),
            'estado' => $this->faker->randomElement(['pendiente', 'en progreso', 'completado']),
        ];
    }
}

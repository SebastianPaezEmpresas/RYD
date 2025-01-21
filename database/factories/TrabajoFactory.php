<?php

namespace Database\Factories;

use App\Models\Trabajo;
use App\Models\User;
use App\Models\TipoTrabajo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TrabajoFactory extends Factory
{
    protected $model = Trabajo::class;

    public function definition()
    {
        $trabajador = User::where('role', 'trabajador')->inRandomOrder()->first();
        if (!$trabajador) {
            $trabajador = User::factory()->create(['role' => 'trabajador']);
        }

        $tipoTrabajo = TipoTrabajo::inRandomOrder()->first();
        if (!$tipoTrabajo) {
            $tipoTrabajo = TipoTrabajo::create([
                'nombre' => 'Mantenimiento General',
                'descripcion' => 'Servicios generales',
                'duracion_estimada' => 120,
                'precio_base' => 150.00
            ]);
        }

        $fechaInicio = fake()->dateTimeBetween('now', '+2 weeks');
        $fechaFin = clone $fechaInicio;
        $fechaFin->modify('+' . rand(2, 8) . ' hours');

        return [
            'titulo' => fake()->sentence(3),
            'descripcion' => fake()->paragraph(),
            'estado' => fake()->randomElement(['pendiente', 'en_progreso', 'completado']),
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'trabajador_id' => $trabajador->id,
            'tipo_trabajo_id' => $tipoTrabajo->id,
            'link_compartido' => Str::random(32),
            'cliente_email' => fake()->email(),
            'calificacion' => fake()->optional()->numberBetween(1, 5),
            'comentario_cliente' => fake()->optional()->sentence(),
            'evidencias_inicio' => null,
            'evidencias_fin' => null,
            'notas_finales' => fake()->optional()->paragraph()
        ];
    }
}
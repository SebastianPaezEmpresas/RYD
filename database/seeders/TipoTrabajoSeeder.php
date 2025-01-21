<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoTrabajo;

class TipoTrabajoSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            [
                'nombre' => 'Mantenimiento General',
                'descripcion' => 'Servicios de mantenimiento general y reparaciones básicas',
                'duracion_estimada' => 120,
                'precio_base' => 150.00
            ],
            [
                'nombre' => 'Jardinería',
                'descripcion' => 'Mantenimiento de jardines y áreas verdes',
                'duracion_estimada' => 180,
                'precio_base' => 200.00
            ],
            [
                'nombre' => 'Electricidad',
                'descripcion' => 'Servicios de instalación y reparación eléctrica',
                'duracion_estimada' => 120,
                'precio_base' => 250.00
            ],
            [
                'nombre' => 'Plomería',
                'descripcion' => 'Servicios de instalación y reparación de plomería',
                'duracion_estimada' => 120,
                'precio_base' => 250.00
            ],
            [
                'nombre' => 'Pintura',
                'descripcion' => 'Servicios de pintura interior y exterior',
                'duracion_estimada' => 240,
                'precio_base' => 300.00
            ]
        ];

        foreach ($tipos as $tipo) {
            TipoTrabajo::create($tipo);
        }
    }
}
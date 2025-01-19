<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Trabajador;
use App\Models\Trabajo;
use App\Models\Encuesta;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Verifica si el usuario ya existe antes de crearlo
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin123'),
            ]);
        }

        // Poblar trabajadores de prueba
        Trabajador::factory(10)->create();

        // Poblar trabajos de prueba
        Trabajo::factory(20)->create();

        // Poblar encuestas de prueba
        Encuesta::factory(15)->create();
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Trabajo;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ejecutar TipoTrabajoSeeder primero
        $this->call(TipoTrabajoSeeder::class);

        // Crear usuario admin
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
            ]);
        }

        // Crear usuario trabajador de ejemplo
        if (!User::where('email', 'trabajador@example.com')->exists()) {
            User::create([
                'name' => 'Trabajador Demo',
                'email' => 'trabajador@example.com',
                'password' => bcrypt('password'),
                'role' => 'trabajador',
                'especialidad' => 'Jardinería'
            ]);
        }

        if (app()->environment('local')) {
            // Crear algunos trabajos de prueba
            Trabajo::factory(5)->create();
        }
    }
}
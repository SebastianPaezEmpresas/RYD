<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Trabajo;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Primero crear usuarios
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
            ]);
        }
    
        if (!User::where('email', 'trabajador@example.com')->exists()) {
            User::create([
                'name' => 'Trabajador Demo',
                'email' => 'trabajador@example.com',
                'password' => bcrypt('password'),
                'role' => 'trabajador',
                'especialidad' => 'Jardinería'
            ]);
        }
    
        // 2. Luego ejecutar TipoTrabajoSeeder
        $this->call(TipoTrabajoSeeder::class);
    
        // 3. Comentar esta parte por ahora
        /*if (app()->environment('local')) {
            Trabajo::factory(5)->create();
        }*/
    }
}
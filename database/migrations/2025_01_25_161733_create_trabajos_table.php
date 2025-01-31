<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trabajos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->foreignId('tipo_trabajo_id')->constrained('tipos_trabajo');
            $table->string('cliente');
            $table->string('direccion');
            $table->text('descripcion');
            $table->decimal('presupuesto', 10, 2);
            $table->enum('estado', ['pendiente', 'en_progreso', 'completado']);
            $table->foreignId('worker_id')->constrained('users');
            $table->date('fecha_programada');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->text('notas')->nullable();
            $table->string('encuesta_token')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trabajos');
    }
};
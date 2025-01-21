<?php
// Tercera migración: create_trabajos_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trabajos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->enum('estado', ['pendiente', 'en_progreso', 'completado'])->default('pendiente');
            $table->datetime('fecha_inicio');
            $table->datetime('fecha_fin');
            $table->foreignId('trabajador_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tipo_trabajo_id')->constrained('tipo_trabajos')->onDelete('cascade');
            $table->string('link_compartido')->unique();
            $table->integer('calificacion')->nullable();
            $table->text('comentario_cliente')->nullable();
            $table->json('evidencias_inicio')->nullable();
            $table->json('evidencias_fin')->nullable();
            $table->text('notas_finales')->nullable();
            $table->string('cliente_email')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trabajos');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('trabajos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable(false);  // Asegurar que el nombre sea obligatorio
            $table->text('descripcion')->nullable(); // Permitir valores nulos en descripción para evitar errores
            $table->enum('estado', ['pendiente', 'en progreso', 'completado'])->default('pendiente'); // Valor por defecto
            $table->unsignedBigInteger('trabajador_id')->nullable(); // Permitir nulos en caso de registros sin trabajador
            $table->foreign('trabajador_id')->references('id')->on('trabajadors')->onDelete('set null'); // Evitar error si se borra el trabajador
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trabajos');
    }
};

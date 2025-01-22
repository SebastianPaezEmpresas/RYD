<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('encuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabajo_id')->constrained()->onDelete('cascade');
            $table->string('token')->unique();
            $table->integer('calificacion_general')->nullable();
            $table->integer('puntualidad')->nullable();
            $table->integer('calidad_trabajo')->nullable();
            $table->integer('profesionalismo')->nullable();
            $table->text('comentarios')->nullable();
            $table->datetime('fecha_envio')->nullable();
            $table->datetime('fecha_respuesta')->nullable();
            $table->string('estado')->default('pendiente'); // pendiente, enviada, respondida
            $table->timestamps();
        });
    }
};

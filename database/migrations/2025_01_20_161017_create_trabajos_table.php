<?php

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
           $table->text('descripcion')->nullable();
           $table->enum('estado', ['pendiente', 'en_progreso', 'completado'])->default('pendiente');
           $table->datetime('fecha_inicio');
           $table->datetime('fecha_fin')->nullable();
           $table->foreignId('trabajador_id')->constrained('trabajadores')->onDelete('cascade');
           $table->foreignId('tipo_trabajo_id')->nullable()->constrained('tipo_trabajos')->onDelete('set null');
           $table->string('link_compartido')->unique()->nullable();
           $table->string('token')->unique()->nullable(); // Agregada la columna token
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
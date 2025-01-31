<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
   {
    Schema::create('trabajo_fotos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('trabajo_id')->constrained();
        $table->string('ruta');
        $table->enum('etapa', ['inicio', 'fin']);
        $table->timestamp('fecha_captura');
        $table->timestamps();
       });
   }

   public function down()
   {
       Schema::dropIfExists('trabajo_fotos');
   }
};
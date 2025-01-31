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
        $table->integer('calificacion');
        $table->text('comentario')->nullable();
        $table->text('sugerencias')->nullable();
        $table->boolean('recomendaria');
        $table->timestamps();
    });
   }

   public function down()
   {
       Schema::dropIfExists('encuestas');
   }
};
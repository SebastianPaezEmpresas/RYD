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
            $table->foreignId('trabajo_id')->constrained('trabajos')->onDelete('cascade');
            $table->integer('puntuacion');
            $table->text('comentario')->nullable();
            $table->boolean('completada')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('encuestas');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('historial_envios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encuesta_id')->constrained()->onDelete('cascade');
            $table->string('tipo');  // 'envio_inicial', 'reenvio'
            $table->string('email');
            $table->string('estado'); // 'exitoso', 'fallido'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('historial_envios');
    }
};
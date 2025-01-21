<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trabajadores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->string('apellido', 255);
            $table->string('email')->unique();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion')->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->date('fecha_contratacion');
            $table->decimal('salario', 10, 2)->nullable();
            $table->string('cargo')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabajadores');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
   {
    Schema::create('trabajo_materiales', function (Blueprint $table) {
        $table->id();
        $table->foreignId('trabajo_id')->constrained()->onDelete('cascade');
        $table->string('material');
        $table->integer('cantidad');
        $table->decimal('costo', 10, 2);
        $table->timestamps();
    });
   }

   public function down()
   {
       Schema::dropIfExists('trabajo_materiales');
   }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trabajos', function (Blueprint $table) {
            // Verifica si la columna existe antes de intentar eliminarla
            if (Schema::hasColumn('trabajos', 'tipo_trabajo_id')) {
                $table->dropForeign(['tipo_trabajo_id']);
                $table->dropColumn('tipo_trabajo_id');
            }
            
            // Agregar la nueva columna solo si no existe
            if (!Schema::hasColumn('trabajos', 'tipo_trabajo')) {
                $table->string('tipo_trabajo')->after('titulo');
            }
        });
    }

    public function down()
    {
        Schema::table('trabajos', function (Blueprint $table) {
            // Verifica si la columna existe antes de eliminarla
            if (Schema::hasColumn('trabajos', 'tipo_trabajo')) {
                $table->dropColumn('tipo_trabajo');
            }

            // Reagregar la clave foránea solo si la columna no existe
            if (!Schema::hasColumn('trabajos', 'tipo_trabajo_id')) {
                $table->foreignId('tipo_trabajo_id')->constrained('tipos_trabajo')->onDelete('cascade');
            }
        });
    }
};

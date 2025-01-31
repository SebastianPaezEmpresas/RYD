<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('trabajos', function (Blueprint $table) {
        $table->decimal('presupuesto', 20, 2)->change();
    });
}

public function down()
{
    Schema::table('trabajos', function (Blueprint $table) {
        $table->decimal('presupuesto', 10, 2)->change();
    });
}
};

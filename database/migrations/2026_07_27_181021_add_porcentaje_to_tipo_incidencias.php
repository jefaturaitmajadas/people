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
        Schema::table('tipo_incidencias', function (Blueprint $table) {
            // Porcentaje a descontar (ej. 100.00, 66.67, 33.33, 0.00)
            $table->decimal('porcentaje_descuento', 5, 2)->default(100.00)->after('descuenta_salario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tipo_incidencias', function (Blueprint $table) {
            $table->dropColumn('porcentaje_descuento');
        });
    }
};

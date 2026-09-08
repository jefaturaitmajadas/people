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
        Schema::table('empresas', function (Blueprint $table) {
            // Agregamos correo y firmante_rrhh. 
            // (Asumimos que 'direccion' y 'telefono' ya existen en tu tabla, si no, puedes agregarlos aquí también).
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable()->after('direccion');
            $table->string('correo')->nullable()->after('telefono');
            $table->string('firmante_rrhh')->nullable()->after('correo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn(['correo', 'firmante_rrhh','telefono','direccion']);
        });
    }
};

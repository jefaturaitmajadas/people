<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forzamos el nombre exacto de la tabla
        Schema::create('empl_temporalidad_contrato', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo'); // Número entero
            $table->string('temporalidad_contrato'); // Ej: Indefinido, Plazo Fijo
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empl_temporalidad_contrato');
    }
};

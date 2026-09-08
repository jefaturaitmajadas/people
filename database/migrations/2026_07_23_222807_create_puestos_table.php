<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forzamos el nombre exacto de la tabla
        Schema::create('puesto', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre del puesto (Ej: Gerente, Cajero)
            $table->text('descriptor_de_puesto'); // Descripción detallada
            $table->text('responsabilidades'); // Responsabilidades del puesto
            $table->text('funciones_generales'); // Funciones generales
            $table->text('funciones_especificas'); // Funciones específicas
            $table->decimal('salario_base', 10, 2); // Salario base con 2 decimales
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('puesto');
    }
};

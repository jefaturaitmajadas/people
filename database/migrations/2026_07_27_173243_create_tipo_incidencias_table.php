<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_incidencias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: Suspensión IGSS, Falta, Permiso con Goce, Permiso sin Goce
            $table->boolean('descuenta_salario')->default(true); // El Motor de Planilla leerá este campo
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_incidencias');
    }
};
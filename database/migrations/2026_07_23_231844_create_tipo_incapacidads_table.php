<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forzamos el nombre exacto de la tabla
        Schema::create('tipo_incapacidad', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: Enfermedad Común, Accidente, Maternidad
            $table->decimal('porcentaje', 5, 2); // Porcentaje total (Ej: 66.67)
            $table->decimal('porcentaje_por_dia', 5, 2); // Porcentaje aplicable por día
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_incapacidad');
    }
};

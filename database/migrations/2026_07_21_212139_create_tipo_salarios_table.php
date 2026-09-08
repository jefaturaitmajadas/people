<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forzamos el nombre exacto de la tabla
        Schema::create('tipo_salario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre del tipo de salario en el sistema
            
            // Relación con el catálogo del IGSS
            $table->foreignId('id_igss_tipo_salario')->constrained('igss_tipo_salario');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_salario');
    }
};

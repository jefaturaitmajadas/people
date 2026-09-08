<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forzamos el nombre exacto de la tabla
        Schema::create('municipio', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre del municipio
            $table->integer('codigo_empl'); // Código de empleador (entero)
            $table->string('codigo_igss'); // Código IGSS (string)
            
            // Relación con el catálogo de departamentos del IGSS
            $table->foreignId('id_igss_departamento')->constrained('igss_departamento');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipio');
    }
};

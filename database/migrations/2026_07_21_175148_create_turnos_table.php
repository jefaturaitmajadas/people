<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forzamos el nombre exacto de la tabla
        Schema::create('turno', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre del turno en el sistema
            
            // Relación con el catálogo de jornadas del empleador
            $table->foreignId('id_empl_jornada')->constrained('empl_jornada_trabajo');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('turno');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forzamos el nombre exacto de la tabla
        Schema::create('nivel_educativo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre del nivel educativo en el sistema
            
            // Relación con el catálogo del empleador
            $table->foreignId('id_empl_nivel_edu')->constrained('empl_nivel_educativo');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nivel_educativo');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forzamos el nombre exacto de la tabla
        Schema::create('estado_civil', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre del estado civil en el sistema
            
            // Relación con el catálogo del empleador
            $table->foreignId('id_empl_estado_civil')->constrained('empl_estado_civil');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estado_civil');
    }
};
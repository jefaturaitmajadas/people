<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nombre exacto de la tabla
        Schema::create('discapacidad', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            
            // Relación con la tabla empl_tipo_discapacidad usando el campo solicitado
            $table->foreignId('id_empl_tipo_capacidad')->constrained('empl_tipo_discapacidad');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discapacidad');
    }
};
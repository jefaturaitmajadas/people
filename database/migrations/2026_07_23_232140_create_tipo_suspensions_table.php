<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forzamos el nombre exacto de la tabla
        Schema::create('tipo_suspension', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre del tipo de suspensión (Ej: Disciplinaria, Por enfermedad, etc.)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_suspension');
    }
};

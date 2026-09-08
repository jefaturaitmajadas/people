<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forzamos el nombre exacto de la tabla
        Schema::create('titulo_academico', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre del título académico (Ej: Licenciatura, Maestría, Perito)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titulo_academico');
    }
};

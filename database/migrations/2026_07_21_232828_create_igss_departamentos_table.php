<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forzamos el nombre exacto de la tabla
        Schema::create('igss_departamento', function (Blueprint $table) {
            $table->id();
            $table->string('departamento'); // Ej: 01, GUATEMALA
            $table->string('nombre'); // Ej: Departamento de Guatemala
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('igss_departamento');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forzamos el nombre exacto de la tabla
        Schema::create('emple_ocupacion', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo'); // Número entero
            $table->string('ocupacion'); // Nombre de la ocupación
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emple_ocupacion');
    }
};
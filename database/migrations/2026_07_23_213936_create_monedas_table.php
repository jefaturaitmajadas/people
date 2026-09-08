<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forzamos el nombre exacto de la tabla
        Schema::create('moneda', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: Quetzal, Dólar, Euro
            $table->string('simbolo'); // Ej: Q, $, €
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moneda');
    }
};

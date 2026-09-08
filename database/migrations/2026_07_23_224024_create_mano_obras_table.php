<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forzamos el nombre exacto de la tabla
        Schema::create('mano_obra', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre de la mano de obra
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mano_obra');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Respetamos el nombre exacto que pediste
        Schema::create('igss_actividad_economica', function (Blueprint $table) {
            $table->id();
            $table->char('categoria', 1); // Un solo caracter
            $table->string('division')->nullable();
            $table->string('grupo')->nullable();
            $table->string('clase')->nullable();
            $table->string('clasificacion')->nullable();
            $table->text('descripcion')->nullable(); // Text por si la descripción es larga
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('igss_actividad_economica');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nombre exacto de la tabla solicitado
        Schema::create('ocupacion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            
            // Relaciones con las tablas creadas anteriormente
            $table->foreignId('id_emple_ocupacion')->constrained('emple_ocupacion');
            $table->foreignId('id_igss_ocupacion')->constrained('igss_ocupacion');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ocupacion');
    }
};

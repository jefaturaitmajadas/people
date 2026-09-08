<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nombre exacto de la tabla solicitado
        Schema::create('igss_pais', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('campo'); // Campo solicitado
            $table->timestamps();
            $table->softDeletes(); // Mantenemos la buena práctica del borrado lógico
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('igss_pais');
    }
};
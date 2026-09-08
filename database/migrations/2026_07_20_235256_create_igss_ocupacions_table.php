<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forzamos el nombre exacto de la tabla
        Schema::create('igss_ocupacion', function (Blueprint $table) {
            $table->id();
            $table->string('gran_grupo')->nullable();
            $table->string('sub_grupo_principal')->nullable();
            $table->string('sub_grupo')->nullable();
            $table->string('grupo_primario')->nullable();
            $table->text('descripcion'); // Texto para la descripción de la ocupación
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('igss_ocupacion');
    }
};
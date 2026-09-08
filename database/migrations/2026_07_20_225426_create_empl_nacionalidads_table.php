<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forzamos el nombre exacto de la tabla
        Schema::create('empl_nacionalidad', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('pais');
            $table->string('nacionalidad');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empl_nacionalidad');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forzamos el nombre exacto de la tabla
        Schema::create('empl_pueblo_pertenencia', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo'); // Número entero
            $table->string('pueblo_de_pertenencia'); // Ej: Maya, Garífuna, Xinca, Ladino/Mestizo
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empl_pueblo_pertenencia');
    }
};

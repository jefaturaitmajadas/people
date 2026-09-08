<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referencia_personal', function (Blueprint $table) {
            $table->id();
            
            // Relación con el empleado (eliminación en cascada)
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            
            // Campos solicitados
            $table->string('nombre');
            $table->string('empresa')->nullable(); // Puede ser opcional en referencias personales, pero lo dejamos como string
            $table->string('telefono');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referencia_personal');
    }
};

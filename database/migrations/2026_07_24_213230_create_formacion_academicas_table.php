<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formacion_academica', function (Blueprint $table) {
            $table->id();
            
            // Relación con el empleado (eliminación en cascada)
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            
            // Campos solicitados
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->foreignId('grado_academico_id')->constrained('grado_academico');
            $table->string('institucion')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formacion_academica');
    }
};

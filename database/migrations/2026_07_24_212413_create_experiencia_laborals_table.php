<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experiencia_laboral', function (Blueprint $table) {
            $table->id();
            
            // Relación con el empleado (Si se borra el empleado, se borran sus experiencias)
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            
            // Campos según tu imagen
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->string('empresa')->nullable();
            $table->string('telefono_empresa')->nullable();
            $table->string('direccion_empresa')->nullable();
            $table->string('puesto')->nullable();
            $table->text('motivo_retiro')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('primer_salario')->nullable(); // En tu imagen dice Texto
            $table->string('segundo_salario')->nullable(); // En tu imagen dice Texto

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experiencia_laboral');
    }
};

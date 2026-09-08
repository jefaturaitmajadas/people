<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horas_extras', function (Blueprint $table) {
            $table->id();
            
            // Relaciones principales
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->foreignId('usuario_creador_id')->constrained('users'); // Quién registró las horas
            
            // Datos del cálculo
            $table->date('fecha_presentacion'); // A qué mes/quincena corresponde
            $table->decimal('cantidad_horas', 8, 2); // Ej: 12.5 horas
            
            // Se guarda el salario por hora en ese momento histórico (por si a futuro el empleado recibe un aumento, no altere reportes pasados)
            $table->decimal('salario_por_hora_sugerido', 10, 2); 
            $table->decimal('factor_multiplicador', 4, 2)->default(1.5); // 1.5 (Normal) o 2.0 (Asuetos)
            $table->decimal('total_pagar', 10, 2); // (horas * salario * factor)
            
            $table->text('observaciones')->nullable();

            // Auditoría del Motor de Planilla
            $table->boolean('pagado')->default(false);
            $table->datetime('fecha_pagada')->nullable();
            $table->foreignId('usuario_confirma_id')->nullable()->constrained('users'); // Quién cerró la planilla
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horas_extras');
    }
};
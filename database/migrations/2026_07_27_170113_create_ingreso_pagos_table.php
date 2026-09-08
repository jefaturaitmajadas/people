<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingreso_pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingreso_id')->constrained('ingresos')->onDelete('cascade');
            
            $table->integer('numero_cuota')->nullable(); 
            $table->decimal('monto', 10, 2);
            $table->date('fecha_pago_proyectada'); // Quincena/mes en que DEBERÍA pagarse
            
            // Auditoría del Motor de Planilla
            $table->boolean('pagado')->default(false); 
            $table->datetime('fecha_pago')->nullable(); 
            $table->foreignId('usuario_confirma_id')->nullable()->constrained('users');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingreso_pagos');
    }
};
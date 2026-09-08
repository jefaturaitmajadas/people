<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prestamo_cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestamo_id')->constrained('prestamos')->onDelete('cascade');
            $table->integer('numero_cuota');
            $table->date('fecha_aplicacion'); // 1 o 16 del mes
            $table->decimal('monto_capital', 10, 2); // 99% (Lo solicitado / cuotas)
            $table->decimal('monto_ahorro', 10, 2); // 1%
            $table->decimal('monto_total', 10, 2); // Suma de capital + ahorro
            $table->boolean('pagado')->default(false);
            $table->date('fecha_pagado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamo_cuotas');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planillas', function (Blueprint $table) {
            $table->id();
            
            $table->string('codigo')->unique(); // Ej: PLA-2026-07-01
            $table->string('descripcion'); // Ej: Primera Quincena Julio 2026
            
            $table->date('fecha_inicio'); // Desde cuándo abarca (ej: 01/07/2026)
            $table->date('fecha_fin'); // Hasta cuándo abarca (ej: 15/07/2026)
            
            $table->enum('tipo_periodo', ['mensual', 'quincenal', 'semanal'])->default('mensual');
            $table->enum('estado', ['borrador', 'cerrada'])->default('borrador'); // Clave para la auditoría
            
            // Totales globales para reportes rápidos a Finanzas
            $table->decimal('total_nomina', 12, 2)->default(0); 
            
            // Auditoría
            $table->foreignId('usuario_creador_id')->constrained('users');
            $table->foreignId('usuario_cierre_id')->nullable()->constrained('users');
            $table->dateTime('fecha_cierre')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planillas');
    }
};
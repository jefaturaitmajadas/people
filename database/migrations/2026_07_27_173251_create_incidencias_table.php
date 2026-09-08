<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->foreignId('tipo_incidencia_id')->constrained('tipo_incidencias');
            $table->foreignId('usuario_creador_id')->constrained('users'); // RRHH que registra
            
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->integer('cantidad_dias'); // Días totales que abarca la incidencia
            
            $table->text('observaciones')->nullable();
            $table->string('documento_adjunto')->nullable(); // Por si quieren subir la boleta del IGSS o carta médica
            
            // --- Auditoría del Motor de Planilla ---
            $table->boolean('aplicado_en_planilla')->default(false); // Cambiará a 1 al cerrar nómina
            $table->datetime('fecha_aplicacion')->nullable();
            $table->foreignId('usuario_confirma_id')->nullable()->constrained('users'); // Quién cerró la planilla
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documento_empleado', function (Blueprint $table) {
            $table->id();
            
            // Relación con el empleado (eliminación en cascada)
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            
            // Relación con tipo de documento
            $table->foreignId('tipo_documento_id')->constrained('tipo_documento');
            
            // Campos solicitados
            $table->string('descripcion');
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento')->nullable();
            $table->text('observaciones')->nullable();

            $table->string('archivo_ruta')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documento_empleado');
    }
};

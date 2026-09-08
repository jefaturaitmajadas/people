<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('igss_tipo_planilla', function (Blueprint $table) {
            $table->id();
            
            // Relación a empresas
            $table->foreignId('id_empresa')->constrained('empresas'); 
            
            $table->integer('identificacion');
            $table->string('nombre');
            
            // Enums
            $table->enum('tipo_afiliado', ['S', 'C']);
            $table->enum('periodo', ['M', 'C', 'S']);
            
            // Relaciones IGSS
            $table->foreignId('id_igss_departamento')->constrained('igss_departamento');
            $table->foreignId('id_actividad')->constrained('igss_actividad_economica');
            
            // Más Enums
            $table->enum('clase_planilla', ['N', 'V']);
            $table->enum('tiempo_contrato', ['TP', 'TC']);
            
            // Campo de concatenación
            $table->string('cadena_concatenada')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('igss_tipo_planilla');
    }
};

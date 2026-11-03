<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('carga_temporal_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carga_temporal_id')->constrained('carga_temporals')->onDelete('cascade');
            
            // Datos del Colaborador
            $table->integer('codigo_empleado')->nullable();
            $table->string('nombres')->nullable();
            $table->string('puesto')->nullable();
            $table->string('departamento')->nullable();
            $table->string('dpi')->nullable();
            
            // Ingresos y Horas
            $table->decimal('salario_hora', 10, 2)->default(0);
            $table->decimal('cantidad_horas_global', 10, 2)->default(0);
            $table->decimal('salario_base', 10, 2)->default(0);
            $table->decimal('cantidad_horas', 10, 2)->default(0); // Cantidad Horas Extras
            $table->decimal('horas_extras', 10, 2)->default(0); // Monto Horas Extras
            $table->decimal('cantidad_horas_dobles', 10, 2)->default(0);
            $table->decimal('horas_dobles', 10, 2)->default(0);
            $table->decimal('comisiones', 10, 2)->default(0);
            $table->decimal('bonificacion', 10, 2)->default(0);
            $table->decimal('bonificacion_incentivo', 10, 2)->default(0);
            $table->decimal('retroactivos', 10, 2)->default(0);
            $table->decimal('total_ingresos', 10, 2)->default(0);
            
            // Deducciones
            $table->decimal('igss_laboral', 10, 2)->default(0);
            $table->decimal('isr', 10, 2)->default(0);
            $table->decimal('anticipos_salario_1', 10, 2)->default(0); // Primera columna del excel
            $table->decimal('anticipos_salario_2', 10, 2)->default(0); // Segunda columna del excel
            $table->decimal('otros_descuentos', 10, 2)->default(0);
            $table->decimal('total_deducciones', 10, 2)->default(0);
            
            // Totales y Clasificación
            $table->decimal('total_neto', 10, 2)->default(0);
            $table->string('forma_pago')->nullable();
            $table->string('tipo_planilla')->nullable();
            
            // Control de Envíos
            $table->boolean('correo_enviado')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carga_temporal_detalles');
    }
};

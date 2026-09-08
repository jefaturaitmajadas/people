<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planilla_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planilla_id')->constrained('planillas')->onDelete('cascade');
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            
            // 1. Días Trabajados (base 30 menos incidencias)
            $table->integer('dias_trabajados'); 
            
            // 2. Cálculo del Sueldo (Guardamos el salario histórico por si a futuro le aumentan)
            $table->decimal('salario_base_mes', 10, 2); 
            $table->decimal('salario_proporcional', 10, 2); // Lo devengado según los días trabajados
            
            // 3. Sumatorias de Módulos Externos
            $table->decimal('total_horas_extras', 10, 2)->default(0);
            $table->decimal('total_ingresos_adicionales', 10, 2)->default(0); // Bonos, Comisiones
            
            // 4. Deducciones
            $table->decimal('igss_laboral', 10, 2)->default(0); // Se calcula sobre Salario Proporcional + Horas Extras (4.83%)
            $table->decimal('total_descuentos', 10, 2)->default(0); // Préstamos, Judiciales, etc.
            
            // 5. Total Final
            $table->decimal('liquido_a_recibir', 10, 2); // Lo que cae a la cuenta bancaria del empleado

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planilla_detalles');
    }
};
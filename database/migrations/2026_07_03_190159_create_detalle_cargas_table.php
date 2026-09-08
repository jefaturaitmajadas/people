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
        Schema::create('detalle_cargas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carga_id')->constrained('cargas')->onDelete('cascade');
            $table->foreignId('empleado_id')->constrained('empleados');
            
            // Usamos decimal(10, 2) para manejar moneda con 2 decimales
            $table->decimal('sueldo_base', 10, 2);
            $table->decimal('salario_diario', 10, 2);
            $table->integer('dias_laborados');
            $table->decimal('horas_extras', 10, 2);
            $table->decimal('salario', 10, 2);
            $table->decimal('desc_igss', 10, 2);
            $table->decimal('salario_liquido', 10, 2);
            $table->decimal('bonificacion_ley', 10, 2);
            $table->decimal('bonificacion_extra', 10, 2);
            $table->decimal('descuentos_varios', 10, 2);
            $table->decimal('isr', 10, 2);
            $table->decimal('liquido', 10, 2);
            
            $table->timestamps();
            $table->softDeletes(); // Borrado lógico
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_cargas');
    }
};

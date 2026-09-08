<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Agregamos el campo cantidad_cuotas a la tabla principal de descuentos
        Schema::table('descuentos', function (Blueprint $table) {
            $table->integer('cantidad_cuotas')->nullable()->after('monto_total');
            $table->boolean('es_recurrente')->default(false)->after('tipo_descuento_id');
        });

        // 2. Creamos la tabla de las transacciones (Tabla de Amortización)
        Schema::create('descuento_cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('descuento_id')->constrained('descuentos')->onDelete('cascade');
            
            $table->integer('numero_cuota'); // Ej: 1, 2, 3...
            $table->decimal('monto', 10, 2);
            $table->date('fecha_cobro_proyectada'); // Quincena/mes en que DEBERÍA cobrarse
            
            // --- TUS NUEVOS CAMPOS DE AUDITORÍA Y CONTROL ---
            $table->boolean('pagado')->default(false); // 0 = Pendiente, 1 = Cobrado en planilla
            $table->datetime('fecha_pago')->nullable(); // Cuándo se confirmó la planilla
            $table->foreignId('usuario_confirma_id')->nullable()->constrained('users'); // Quién lo autorizó
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('descuento_cuotas');
        Schema::table('descuentos', function (Blueprint $table) {
            $table->dropColumn(['cantidad_cuotas', 'es_recurrente']);
        });
    }
};

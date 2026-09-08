<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('descuentos', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->foreignId('tipo_descuento_id')->constrained('tipo_descuentos');
            
            $table->string('descripcion'); // Ej: Préstamo de emergencia
            $table->decimal('monto_total', 10, 2)->nullable(); // Útil para préstamos (Ej: Q5,000)
            $table->decimal('cuota', 10, 2); // Lo que se descuenta en cada nómina (Ej: Q500)
            $table->decimal('saldo', 10, 2)->nullable(); // Lo que falta por pagar
            
            $table->date('fecha_inicio'); // Cuándo empieza a descontarse
            $table->date('fecha_fin')->nullable(); // Cuándo termina (opcional, puede terminar cuando el saldo llegue a 0)
            
            $table->boolean('estado')->default(true); // Activo (1) o Finalizado/Inactivo (0)
            $table->text('observaciones')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('descuentos');
    }
};

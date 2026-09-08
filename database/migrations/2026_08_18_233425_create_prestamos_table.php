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
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->decimal('monto_solicitado', 10, 2);
            $table->decimal('monto_total', 10, 2); // Solicitado + 1%
            $table->enum('frecuencia', ['Mensual', 'Quincenal']);
            $table->integer('cantidad_cuotas');
            // Estados: borrador (para firmar), activo (aprobado y descontando), pagado
            $table->enum('estado', ['Borrador', 'Activo', 'Pagado'])->default('Borrador');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};

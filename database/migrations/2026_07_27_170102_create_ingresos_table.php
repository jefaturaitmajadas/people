<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->foreignId('tipo_ingreso_id')->constrained('tipo_ingresos');
            
            $table->boolean('es_recurrente')->default(true);
            $table->string('descripcion'); 
            
            $table->decimal('monto_total', 10, 2)->nullable(); // Útil si es un bono grande fraccionado
            $table->integer('cantidad_cuotas')->nullable(); 
            $table->decimal('monto_cuota', 10, 2); // Lo que se suma en cada nómina
            
            $table->date('fecha_inicio'); 
            $table->date('fecha_fin')->nullable(); 
            
            $table->boolean('estado')->default(true); 
            $table->text('observaciones')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingresos');
    }
};

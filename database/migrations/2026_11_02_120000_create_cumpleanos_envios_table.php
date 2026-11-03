<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bitácora de felicitaciones enviadas (evita enviar dos veces el mismo año)
        Schema::create('cumpleanos_envios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->cascadeOnDelete();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedSmallInteger('anio');
            $table->string('correo');
            $table->timestamp('enviado_at');
            $table->timestamps();

            $table->unique(['empleado_id', 'anio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cumpleanos_envios');
    }
};

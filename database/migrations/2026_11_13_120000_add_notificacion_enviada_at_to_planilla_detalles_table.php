<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Marca cuándo se envió la boleta por correo al empleado; el envío masivo
    // solo toma los pendientes, así se puede reintentar sin duplicar correos.
    public function up(): void
    {
        Schema::table('planilla_detalles', function (Blueprint $table) {
            $table->timestamp('notificacion_enviada_at')->nullable()->after('liquido_a_recibir');
        });
    }

    public function down(): void
    {
        Schema::table('planilla_detalles', function (Blueprint $table) {
            $table->dropColumn('notificacion_enviada_at');
        });
    }
};

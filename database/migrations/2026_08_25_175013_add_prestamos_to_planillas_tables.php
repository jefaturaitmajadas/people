<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Agregamos pago_prestamos al detalle de la planilla
        Schema::table('planilla_detalles', function (Blueprint $table) {
            $table->decimal('pago_prestamos', 10, 2)->default(0)->after('isr');
        });

        // 2. Agregamos planilla_id a las cuotas para saber en qué planilla se descontó
        Schema::table('prestamo_cuotas', function (Blueprint $table) {
            $table->foreignId('planilla_id')->nullable()->after('prestamo_id')->constrained('planillas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('planilla_detalles', function (Blueprint $table) {
            $table->dropColumn('pago_prestamos');
        });

        Schema::table('prestamo_cuotas', function (Blueprint $table) {
            $table->dropForeign(['planilla_id']);
            $table->dropColumn('planilla_id');
        });
    }
};
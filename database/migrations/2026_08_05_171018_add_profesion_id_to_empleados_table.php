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
        Schema::table('empleados', function (Blueprint $table) {
            // Lo ponemos nullable por si algún empleado no tiene profesión registrada
            $table->foreignId('profesion_id')->nullable()->after('puesto_id')->constrained('profesiones')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropForeign(['profesion_id']);
            $table->dropColumn('profesion_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // El jefe es otro empleado (ver Empleado::jefe), no un usuario del sistema
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropForeign(['jefe_id']);
            $table->foreign('jefe_id')->references('id')->on('empleados')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropForeign(['jefe_id']);
            $table->foreign('jefe_id')->references('id')->on('users');
        });
    }
};

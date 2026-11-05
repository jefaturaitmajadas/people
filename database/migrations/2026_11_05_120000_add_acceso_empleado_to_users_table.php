<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Acceso de colaboradores: entran con su identificación y no necesitan correo
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['administrador', 'gerente', 'generalista', 'empleado'])->default('generalista')->change();
            $table->string('email')->nullable()->change();
            $table->string('usuario')->nullable()->unique()->after('name');
            $table->foreignId('empleado_id')->nullable()->unique()->after('rol')->constrained('empleados')->nullOnDelete();
            $table->boolean('debe_cambiar_password')->default(false)->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('empleado_id');
            $table->dropUnique(['usuario']);
            $table->dropColumn(['usuario', 'debe_cambiar_password']);
        });
    }
};

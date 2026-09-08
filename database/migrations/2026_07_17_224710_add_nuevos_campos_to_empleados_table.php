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
            // Eliminamos las columnas anteriores
            $table->dropColumn(['nombres', 'apellidos']);

            // Agregamos los nombres separados
            $table->string('primer_nombre')->after('codigo_empleado');
            $table->string('segundo_nombre')->nullable()->after('primer_nombre');
            $table->string('primer_apellido')->after('segundo_nombre');
            $table->string('segundo_apellido')->nullable()->after('primer_apellido');

            // Agregamos llaves foráneas y nuevos datos
            $table->foreignId('tipo_identificacion_id')->nullable()->constrained('tipo_identificacions');
            $table->string('numero_identificacion')->nullable();
            
            $table->foreignId('departamento_id')->nullable()->constrained('departamentos');
            // Relación recursiva (un jefe también es un empleado)
            $table->foreignId('jefe_id')->nullable()->constrained('empleados');

            $table->string('telefono_casa')->nullable();
            $table->string('telefono_movil')->nullable();
            $table->string('foto_perfil')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            //
        });
    }
};

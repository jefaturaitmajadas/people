<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('jefe_id')->nullable()->constrained('users')->onDelete('cascade');
            

            // --- DATOS BASE (Agregados por lógica) ---
            $table->string('primer_nombre');
            $table->string('segundo_nombre');
            $table->string('primer_apellido');
            $table->string('segundo_apellido');
            $table->string('casada_apellido')->nullable();
            $table->string('telefono')->nullable();
            $table->string('telefono_casa')->nullable();
            $table->string('codigo_empleado')->unique()->nullable();
            $table->foreignId('identificacion_id')->nullable()->constrained('empl_doc_identificacion')->onDelete('cascade');
            $table->string('identificacion')->unique()->nullable();
            $table->text('foto_perfil')->nullable();


            
            // --- GENERAL ---
            $table->string('correo_corporativo')->nullable();
            $table->string('correo_personal')->nullable();

            // --- GESTIÓN ---
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_salida')->nullable();
            $table->boolean('genera_planilla')->default(true);
            $table->foreignId('tipo_contrato_id')->nullable()->constrained('tipo_contrato');
            $table->boolean('contratable')->default(true);
            $table->foreignId('turno_id')->nullable()->constrained('turno');
            $table->string('poliza')->nullable();
            $table->foreignId('tipo_salario_id')->nullable()->constrained('tipo_salario');
            $table->foreignId('departamento_id')->nullable()->constrained('departamentos'); // Unidad de Negocio

            // --- PERSONAL ---
            $table->string('nit')->nullable();
            $table->string('no_igss')->nullable();
            $table->string('irtra')->nullable();
            $table->foreignId('genero_id')->nullable()->constrained('genero');
            $table->foreignId('estado_civil_id')->nullable()->constrained('estado_civil');
            $table->foreignId('religion_id')->nullable()->constrained('religion');
            $table->boolean('aplica_credito_fiscal_isr')->default(false);
            $table->date('fecha_nacimiento')->nullable();
            $table->foreignId('pais_nacimiento_id')->nullable()->constrained('pais');
            $table->foreignId('municipio_nacimiento_id')->nullable()->constrained('municipio');
            $table->foreignId('nivel_educativo_id')->nullable()->constrained('nivel_educativo');
            $table->foreignId('pueblo_id')->nullable()->constrained('pueblo');
            $table->foreignId('comunidad_linguistica_id')->nullable()->constrained('comunidad_linguistica');
            $table->string('vive_con')->nullable();
            $table->foreignId('tipo_casa_id')->nullable()->constrained('tipo_casa');
            $table->boolean('vehiculo')->default(false);
            $table->foreignId('tipo_transporte_id')->nullable()->constrained('tipo_transporte');
            $table->integer('cantidad_hijos')->nullable()->default(0);
            $table->integer('cantidad_dependientes')->nullable()->default(0);
            $table->string('contacto_emergencia')->nullable();
            $table->string('telefono_contacto_emergencia')->nullable();
            $table->foreignId('pais_residencia_id')->nullable()->constrained('pais');
            $table->text('direccion_exacta')->nullable();
            $table->foreignId('discapacidad_id')->nullable()->constrained('discapacidad');
            $table->foreignId('ocupacion_id')->nullable()->constrained('ocupacion');

            // --- FAMILIAR ---
            $table->string('nombre_padre')->nullable();
            $table->string('telefono_padre')->nullable();
            $table->string('nombre_madre')->nullable();
            $table->string('telefono_madre')->nullable();
            $table->string('nombre_conyuge')->nullable();
            $table->string('telefono_conyuge')->nullable();

            // --- FINANZAS ---
            $table->decimal('salario', 10, 2)->nullable();
            $table->foreignId('tipo_pago_id')->nullable()->constrained('tipo_pago');
            $table->foreignId('moneda_id')->nullable()->constrained('moneda');
            $table->boolean('salario_fijo')->default(false);
            $table->decimal('salario_por_hora', 10, 2)->nullable();
            $table->foreignId('seguro_id')->nullable()->constrained('seguro');
            $table->boolean('pertenece_asociacion')->default(false);
            $table->boolean('pension_alimentaria')->default(false);
            $table->decimal('monto_pension', 10, 2)->nullable();
            $table->boolean('embargo')->default(false);
            $table->decimal('monto_embargo', 10, 2)->nullable();
            $table->foreignId('banco_id')->nullable()->constrained('banco');
            $table->string('numero_cuenta')->nullable();
            $table->foreignId('tipo_cuenta_id')->nullable()->constrained('tipo_cuenta');

            // --- NÓMINA ---
            $table->foreignId('puesto_id')->nullable()->constrained('puesto');
            $table->foreignId('igss_tipo_planilla_id')->nullable()->constrained('igss_tipo_planilla');
            $table->foreignId('centro_costo_id')->nullable()->constrained('centro_costo');
            $table->boolean('marca_reloj')->default(false);
            $table->foreignId('mano_obra_id')->nullable()->constrained('mano_obra');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ScopesByEmpresa;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empleado extends Model
{
    use SoftDeletes;
    use ScopesByEmpresa;
    protected $table = 'empleados';

    protected $fillable = [
        'empresa_id','jefe_id','primer_nombre','segundo_nombre','primer_apellido', 'segundo_apellido','casada_apellido', 'codigo_empleado', 'identificacion_id' ,'identificacion', 'correo_corporativo',
        'correo_personal','fecha_inicio', 'fecha_salida', 'genera_planilla', 'tipo_contrato_id', 'contratable',
        'turno_id', 'poliza', 'tipo_salario_id', 'departamento_id',
        'nit', 'no_igss', 'irtra', 'genero_id', 'estado_civil_id', 'religion_id',
        'aplica_credito_fiscal_isr', 'fecha_nacimiento', 'pais_nacimiento_id', 'municipio_nacimiento_id',
        'nivel_educativo_id', 'pueblo_id', 'comunidad_linguistica_id', 'vive_con', 'tipo_casa_id',
        'vehiculo', 'tipo_transporte_id', 'cantidad_hijos', 'cantidad_dependientes',
        'contacto_emergencia', 'telefono_contacto_emergencia', 'pais_residencia_id', 'direccion_exacta',
        'discapacidad_id', 'ocupacion_id',
        'nombre_padre', 'telefono_padre', 'nombre_madre', 'telefono_madre', 'nombre_conyuge', 'telefono_conyuge',
        'salario', 'tipo_pago_id', 'moneda_id', 'salario_fijo', 'salario_por_hora', 'seguro_id',
        'pertenece_asociacion', 'pension_alimentaria', 'monto_pension', 'embargo', 'monto_embargo',
        'banco_id', 'numero_cuenta', 'tipo_cuenta_id',
        'puesto_id', 'igss_tipo_planilla_id', 'centro_costo_id', 'marca_reloj', 'mano_obra_id','foto_perfil','telefono','telefono_casa','profesion_id',
        'dias_adicionales_vacaciones'
    ];

    // --- Relaciones ---
    public function tipoIdentificacion() { return $this->belongsTo(EmplDocIdentificacion::class, 'identificacion_id'); }
    public function empresa() { return $this->belongsTo(Empresa::class); }
    public function usuario() { return $this->hasOne(User::class); }
    public function tipoContrato() { return $this->belongsTo(TipoContrato::class); }
    public function turno() { return $this->belongsTo(Turno::class); }
    public function tipoSalario() { return $this->belongsTo(TipoSalario::class); }
    public function departamento() { return $this->belongsTo(Departamento::class); }
    public function genero() { return $this->belongsTo(Genero::class); }
    public function estadoCivil() { return $this->belongsTo(EstadoCivil::class); }
    public function religion() { return $this->belongsTo(Religion::class); }
    public function paisNacimiento() { return $this->belongsTo(Pais::class, 'pais_nacimiento_id'); }
    public function municipioNacimiento() { return $this->belongsTo(Municipio::class, 'municipio_nacimiento_id'); }
    public function nivelEducativo() { return $this->belongsTo(NivelEducativo::class); }
    public function pueblo() { return $this->belongsTo(Pueblo::class); }
    public function comunidadLinguistica() { return $this->belongsTo(ComunidadLinguistica::class); }
    public function tipoCasa() { return $this->belongsTo(TipoCasa::class); }
    public function tipoTransporte() { return $this->belongsTo(TipoTransporte::class); }
    public function paisResidencia() { return $this->belongsTo(Pais::class, 'pais_residencia_id'); }
    public function discapacidad() { return $this->belongsTo(Discapacidad::class); }
    public function ocupacion() { return $this->belongsTo(Ocupacion::class); }
    public function tipoPago() { return $this->belongsTo(TipoPago::class); }
    public function moneda() { return $this->belongsTo(Moneda::class); }
    public function seguro() { return $this->belongsTo(Seguro::class); }
    public function banco() { return $this->belongsTo(Banco::class); }
    public function tipoCuenta() { return $this->belongsTo(TipoCuenta::class); }
    public function puesto() { return $this->belongsTo(Puesto::class); }
    public function igssTipoPlanilla() { return $this->belongsTo(IgssTipoPlanilla::class); }
    public function centroCosto() { return $this->belongsTo(CentroCosto::class); }
    public function manoObra() { return $this->belongsTo(ManoObra::class); }
      // Relación recursiva: Obtener el jefe de este empleado
    public function jefe() { return $this->belongsTo(Empleado::class, 'jefe_id'); }

    public function profesion()
    {
        return $this->belongsTo(Profesion::class, 'profesion_id');
    }

    public function experienciasLaborales() 
    { 
        return $this->hasMany(ExperienciaLaboral::class); 
    }

    public function formacionesAcademicas() 
    { 
        return $this->hasMany(FormacionAcademica::class); 
    }

    public function documentos() 
    { 
        return $this->hasMany(DocumentoEmpleado::class); 
    }

    public function referenciasLaborales() 
    { 
        return $this->hasMany(ReferenciaLaboral::class); 
    }

    public function referenciasPersonales() 
    { 
        return $this->hasMany(ReferenciaPersonal::class); 
    }

    public function descuentos() 
    { 
        return $this->hasMany(Descuento::class); 
    }

    public function ingresos() 
    { 
        return $this->hasMany(Ingreso::class); 
    }

    public function horasExtras() { return $this->hasMany(HoraExtra::class); }

    public function incidencias() 
    { 
        return $this->hasMany(Incidencia::class); 
    }

    public function planillasDetalles() 
    { 
        return $this->hasMany(PlanillaDetalle::class); 
    }

    // 1. Relación con el historial de vacaciones
    public function vacaciones()
    {
        return $this->hasMany(EmpleadoVacacion::class, 'empleado_id');
    }

    // 2. Motor de Cálculo de Saldo
    public function calcularSaldoVacaciones()
    {
        $empresaId = session('empresa_activa_id');
        $hoy = \Carbon\Carbon::now();
        $fechaIngreso = \Carbon\Carbon::parse($this->fecha_inicio);
        
        // Tomamos los días adicionales del perfil del empleado (Si es null, usamos 0)
        $diasAdicionales = $this->dias_adicionales_vacaciones ?? 0;
        
        $diasAcumulados = 0;
        $fechaBase = $fechaIngreso; 

        // REGLA DE NEGOCIO: COLAPERSA (ID 6)
        if ($empresaId == 6) {
            $anioCorte = $hoy->month >= 2 ? $hoy->year : $hoy->year - 1;
            $fechaCorte = \Carbon\Carbon::parse($anioCorte . '-02-01');

            if ($fechaIngreso->lessThan($fechaCorte)) {
                $diasLaborados = $fechaCorte->diffInDays($hoy);
                $fechaBase = $fechaCorte;
            } else {
                $diasLaborados = $fechaIngreso->diffInDays($hoy);
            }
            
            // Cálculo base + sus días adicionales dinámicos
            $diasAcumulados = ($diasLaborados * 0.0412) + $diasAdicionales;

        } else {
            // Lógica para las DEMÁS EMPRESAS
            $diasLaborados = $fechaIngreso->diffInDays($hoy);
            // Cálculo de ley + sus días adicionales dinámicos (si la empresa no da, el campo estará en 0)
            $diasAcumulados = (($diasLaborados / 365) * 15) + $diasAdicionales;
        }

        // Obtener la suma de días que ya solicitó y tomó el empleado (Sin los paréntesis para evitar N+1)
        $diasTomados = $this->vacaciones->sum('dias_tomados');
        
        return [
            'fecha_base' => $fechaBase->format('d/m/Y'),
            'dias_acumulados' => round($diasAcumulados, 2),
            'dias_ley' => round($diasAcumulados - $diasAdicionales, 2),
            'dias_goze' => round($diasAdicionales, 2),
            'dias_tomados' => round($diasTomados, 2),
            'saldo_disponible' => round($diasAcumulados - $diasTomados, 2),
            'es_colapersa' => ($empresaId == 6)
        ];
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }

    public function calcularDisponibilidadPrestamo()
    {
        $hoy = \Carbon\Carbon::now();
        $fechaInicio = \Carbon\Carbon::parse($this->fecha_inicio);
        
        // 1. Días laborados
        // Se suma 1 para que el día de inicio cuente
        $diasLaborados = $fechaInicio->diffInDays($hoy) + 1;
        
        // 2. Integración Salarial: Salario + (Salario/12)*2
        $salarioBase = $this->salario ?? 0;
        $salarioIntegrado = $salarioBase + (($salarioBase / 12) * 2);
        
        // 3. Proyección Total
        $proyectado = ($salarioIntegrado / 365) * $diasLaborados;
        //dd($proyectado);
        // 4. Disponible Bruto (70% del proyectado)
        $disponibleBruto = $proyectado * 0.70;

        // 5. Restar Capital Pendiente (De todos los préstamos activos)
        // Sumamos todas las cuotas de este empleado que NO estén pagadas
        $capitalPendiente = \DB::table('prestamo_cuotas')
            ->join('prestamos', 'prestamo_cuotas.prestamo_id', '=', 'prestamos.id')
            ->where('prestamos.empleado_id', $this->id)
            ->where('prestamo_cuotas.pagado', false)
            ->sum('prestamo_cuotas.monto_capital');

        // 6. Disponible Final Neto
        $disponibleNeto = $disponibleBruto - $capitalPendiente;

        return [
            'dias_laborados' => round($diasLaborados),
            'salario_integrado' => round($salarioIntegrado, 2),
            'disponible_bruto' => round($disponibleBruto, 2),
            'capital_pendiente' => round($capitalPendiente, 2),
            // Si por alguna razón el pendiente es mayor, devolvemos 0 para no dar saldos negativos
            'disponible_neto' => $disponibleNeto > 0 ? round($disponibleNeto, 2) : 0,
        ];
    }

    // --- Cumpleañeros ---

    // Empleados que cumplen años en el mes indicado y estaban activos en ese mes/año
    public function scopeCumpleanerosDelMes($query, int $mes, int $anio)
    {
        $finMes = \Carbon\Carbon::create($anio, $mes, 1)->endOfMonth()->toDateString();

        return $query->whereNotNull('fecha_nacimiento')
            ->whereMonth('fecha_nacimiento', $mes)
            ->where(function ($q) use ($finMes) {
                $q->whereNull('fecha_inicio')->orWhere('fecha_inicio', '<=', $finMes);
            })
            // Solo colaboradores sin fecha de baja (los que ya salieron no se muestran)
            ->whereNull('fecha_salida')
            ->orderByRaw('DAY(fecha_nacimiento) asc')
            ->orderBy('primer_nombre', 'asc');
    }

    // Correo para notificaciones: corporativo y, si no tiene, el personal
    public function getCorreoNotificacionAttribute()
    {
        if (filled($this->correo_corporativo)) {
            return trim($this->correo_corporativo);
        }

        return filled($this->correo_personal) ? trim($this->correo_personal) : null;
    }

    public function getNombreCortoAttribute()
    {
        return trim($this->primer_nombre . ' ' . $this->primer_apellido);
    }

    public function getNombreCompletoAttribute()
    {
        return trim(preg_replace('/\s+/', ' ', implode(' ', [
            $this->primer_nombre, $this->segundo_nombre, $this->primer_apellido, $this->segundo_apellido,
        ])));
    }
}
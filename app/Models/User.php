<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'usuario', 'email', 'password', 'rol', 'empleado_id', 'debe_cambiar_password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'debe_cambiar_password' => 'boolean',
        ];
    }

    // Relación: Un usuario puede tener muchas empresas
    public function empresas()
    {
        return $this->belongsToMany(Empresa::class);
    }

    // Helpers para verificar roles fácilmente
    public function isAdministrador()
    {
        return $this->rol === 'administrador';
    }

    public function isEmpleado()
    {
        return $this->rol === 'empleado';
    }

    // Relación: El usuario de un colaborador (sin filtro de empresa activa, se usa al iniciar sesión)
    public function empleado()
    {
        return $this->belongsTo(Empleado::class)->withoutGlobalScope('empresa_activa');
    }

    // El usuario es la identificación sin espacios ni guiones
    public static function normalizarUsuario($valor)
    {
        return preg_replace('/[\s\-]+/', '', trim((string) $valor));
    }

    // Crea o actualiza el acceso del colaborador. Devuelve null si no se le puede dar acceso
    public static function sincronizarAccesoEmpleado(Empleado $empleado)
    {
        $usuario = static::normalizarUsuario($empleado->identificacion);

        if ($usuario === '' || $empleado->fecha_salida) {
            return null;
        }

        $repetido = static::withTrashed()
            ->where('usuario', $usuario)
            ->where('empleado_id', '!=', $empleado->id)
            ->exists();

        if ($repetido) {
            return null;
        }

        $user = static::withTrashed()->where('empleado_id', $empleado->id)->first();

        if (!$user) {
            return static::create([
                'name' => $empleado->nombre_completo,
                'usuario' => $usuario,
                'password' => $usuario,
                'rol' => 'empleado',
                'empleado_id' => $empleado->id,
                'debe_cambiar_password' => true,
            ]);
        }

        $datos = ['name' => $empleado->nombre_completo, 'usuario' => $usuario];

        // Si corrigen la identificación antes de su primer ingreso, la clave inicial también cambia
        if ($user->usuario !== $usuario && $user->debe_cambiar_password) {
            $datos['password'] = $usuario;
        }

        $user->update($datos);

        return $user;
    }
}

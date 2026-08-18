<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

/**
 * Modelo de Usuario del Sistema SIGEDOR.
 *
 * Representa a los usuarios del sistema (Administradores, Jefes de Área y Docentes),
 * integrando autenticación, autorización basada en roles (Spatie), borrado lógico
 * y control de acceso a paneles de Filament.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property bool $is_active
 * @property bool $is_approved
 * @property int|null $sede_id
 * @property int|null $area_id
 */
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $table = 'users';

    /**
     * Atributos asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'is_approved',
        'sede_id',
        'area_id',
    ];

    /**
     * Atributos ocultos en serialización.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atributos casteados.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'is_approved' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Relación con la Sede asignada.
     */
    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    /**
     * Relación con el Área asignada.
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Perfil docente asociado al usuario.
     */
    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }

    /**
     * Verifica si el usuario tiene permiso para acceder al panel de Filament.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) ($this->is_active && $this->is_approved);
    }

    /**
     * Verifica si el usuario es Administrador.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Verifica si el usuario es Jefe de Área.
     */
    public function isAreaManager(): bool
    {
        return $this->hasRole('area_manager');
    }

    /**
     * Verifica si el usuario es Docente.
     */
    public function isTeacher(): bool
    {
        return $this->hasRole('teacher');
    }

    /**
     * Verifica si el usuario está aprobado.
     */
    public function isApproved(): bool
    {
        return (bool) $this->is_approved;
    }

    /**
     * Verifica si el usuario está activo.
     */
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * Obtiene el nombre descriptivo del rol principal del usuario.
     */
    public function getFullRoleName(): string
    {
        return match ($this->roles->first()?->name) {
            'admin' => 'Administrador',
            'area_manager' => 'Jefe de Área',
            'teacher' => 'Profesor',
            default => 'Sin rol',
        };
    }

    /**
     * Scope para usuarios activos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para usuarios aprobados.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope para usuarios pendientes de aprobación.
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Site;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\SiteOption;
use App\Models\AreaOption;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'role',
        'is_active', 'is_approved', 'email_verified_at',
        'site_option_id',
        'area_option_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'is_approved' => 'boolean',
        'role.name' => 'string',
        'site.name' => 'string',
    ];

    // Relationships
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    public function siteOption()
    {
        return $this->belongsTo(SiteOption::class, 'site_option_id');
    }

    public function areaOption()
    {
        return $this->belongsTo(AreaOption::class, 'area_option_id');
    }

    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    // Helper methods
    public function isApproved(): bool
    {
        return $this->is_approved;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function getFullRoleName(): string
    {
        return match ($this->roles->first()?->name) {
            'admin' => 'Administrador',
            'area_manager' => 'Jefe de Área',
            'teacher' => 'Profesor',
            default => 'Sin rol'
        };
    }

    public function canAccessFilament(): bool
    {
        return $this->is_active && $this->is_approved;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isAreaManager(): bool
    {
        return $this->hasRole('area_manager');
    }

    public function isTeacher(): bool
    {
        return $this->hasRole('teacher');
    }

    public function getSiteId()
    {
        return $this->site_id;
    }

    public function getArea()
    {
        return $this->site?->area;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true; // Permite acceso al panel a todos los usuarios (puedes refinar esto con roles/permisos)
    }

    public function canManageTeacher(Teacher $teacher): bool
    {
        if ($this->hasRole('admin')) {
            return true;
        }

        if ($this->hasRole('area_manager')) {
            return $this->site_id === $teacher->site_id;
        }

        if ($this->hasRole('teacher')) {
            return $this->cdi === $teacher->cdi;
        }

        return false;
    }

    protected static function booted(): void
    {
        // Eliminar cualquier scope que filtre usuarios
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->password = Hash::make($user->password);
        });
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function siteOptions(): BelongsToMany
    {
        return $this->belongsToMany(SiteOption::class);
    }

    public function getTenants(Panel $panel): array
    {
        return $this->siteOptions()->get()->toArray();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->siteOptions->contains($tenant);
    }
}

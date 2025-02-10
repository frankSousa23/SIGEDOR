<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Sede;
use App\Models\Areas;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'is_approved' => 'boolean',
    ];

    // Relationships
    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function areas()
    {
        return $this->belongsToMany(Area::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
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

    public function isAreaManager()
    {
    return $this->hasRole('area_manager') &&
           $this->sede_id &&
           $this->areas()->exists();
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

    public function canAccessPanel(): bool
    {
        return $this->is_approved && $this->is_active;
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

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->password = Hash::make($user->password);
        });
    }

    public function scopeForAreaManager($query)
    {
        return $query->whereHas('roles', function($q) {
                $q->where('name', 'area_manager');
            })
            ->when(auth()->user()->isAreaManager(), function($q) {
                $q->where('site_id', auth()->user()->site_id)
                ->whereHas('areas', function($q) {
                    $q->whereIn('id', auth()->user()->areas->pluck('id'));
                });
            });
    }

}

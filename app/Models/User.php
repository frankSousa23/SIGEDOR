<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Site;
use App\Models\Teacher;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cdi',
        'site_id',
        'is_active',
        'is_approved'
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
        'password' => 'hashed',
        'is_active' => 'boolean',
        'is_approved' => 'boolean'
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'cdi', 'cdi');
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
}

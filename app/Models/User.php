<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role; // Added this line to import the Role model

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class)
            ->withPivot(['site_id', 'area'])
            ->withTimestamps();
    }

    public function hasRole($role)
    {
        return $this->roles()->where('slug', $role)->exists();
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isAreaManager()
    {
        return $this->hasRole('area-manager');
    }

    public function isTeacher()
    {
        return $this->hasRole('teacher');
    }

    public function getSiteId()
    {
        return $this->roles()->first()?->pivot->site_id;
    }

    public function getArea()
    {
        return $this->roles()->first()?->pivot->area;
    }
}

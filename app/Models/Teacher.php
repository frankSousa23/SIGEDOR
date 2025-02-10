<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Site;
use App\Models\Category;
use App\Models\Dedication;
use App\Models\PermissionTeacher;
use App\Models\Report;
use App\Models\User;
use App\Models\Sede;
use App\Models\Area;
use App\Models\Programa;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'cdi',
        'name',
        'surName',
        'genre',
        'phone',
        'email',
        'birthDate',
        'datePromotion',
        'asignaturePromotion',
        'user_id',
        'sede_id',
        'category_id',
        'dedication_id',
        'has_site',
        'has_category',
        'has_dedication',
        'has_permission',
        'is_completed'
    ];

    protected $casts = [
        'birthDate' => 'date',
        'datePromotion' => 'date',
        'sede_id' => 'integer',
        'has_site' => 'boolean',
        'has_category' => 'boolean',
        'has_dedication' => 'boolean',
        'has_permission' => 'boolean',
        'is_completed' => 'boolean'
    ];

    // Relación con Sede
    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    // Relación many-to-many con Area
    public function areas(): BelongsToMany
    {
        return $this->belongsToMany(Area::class, 'area_teacher')->withTimestamps();
    }

    // Relación con User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function sites()
    {
        return $this->belongsToMany(Site::class)
            ->withTimestamps();
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function dedication()
    {
        return $this->belongsTo(Dedication::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function permissionTeachers()
    {
        return $this->hasMany(PermissionTeacher::class);
    }

    public function programas()
    {
        return $this->belongsToMany(Programa::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->name} {$this->surName}";
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }


}

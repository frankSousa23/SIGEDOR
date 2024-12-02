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

class Teacher extends Model
{
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
        'site_id',
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
        'has_site' => 'boolean',
        'has_category' => 'boolean',
        'has_dedication' => 'boolean',
        'has_permission' => 'boolean',
        'is_completed' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function dedication(): BelongsTo
    {
        return $this->belongsTo(Dedication::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function permissionTeacher(): HasMany
    {
        return $this->hasMany(PermissionTeacher::class);
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

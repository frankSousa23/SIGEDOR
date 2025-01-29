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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SiteOption;
use App\Models\AreaOption;

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
        'site_option_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function siteOption()
    {
        return $this->belongsTo(SiteOption::class);
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

    public function areaOption()
    {
        return $this->belongsTo(AreaOption::class);
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\PermissionTeacher;

class Teacher extends Model
{
    //
    protected $fillable = [
        'user_id',
        'site_id',
        'name',
        'ci',
        'phone',
        'address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function category(): HasOne
    {
        return $this->hasOne(Category::class);
    }

    public function dedication(): HasOne
    {
        return $this->hasOne(Dedication::class);
    }

    public function permissionTeachers(): HasMany
    {
        return $this->hasMany(PermissionTeacher::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}

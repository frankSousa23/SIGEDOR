<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermissionTeacher extends Model
{
    protected $table = 'permissionsteachers';

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'teacher_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}

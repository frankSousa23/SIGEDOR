<?php

namespace App\Observers;

use App\Models\Teacher;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class TeacherObserver
{
    public function creating(Teacher $teacher)
    {
        throw_unless(
            Gate::check('create', $teacher),
            AuthorizationException::class
        );
    }
}

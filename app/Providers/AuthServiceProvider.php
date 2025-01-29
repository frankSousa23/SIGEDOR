<?php

namespace App\Providers;

use App\Models\Teacher;
use App\Policies\TeacherPolicy;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Teacher::class => TeacherPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Definir políticas para UserResource
        Gate::define('view users', [UserPolicy::class, 'viewAny']);
        Gate::define('create users', [UserPolicy::class, 'create']);
        Gate::define('edit users', [UserPolicy::class, 'update']);
        Gate::define('delete users', [UserPolicy::class, 'delete']);

        // Definir políticas para TeacherResource
        Gate::define('view teachers', [TeacherPolicy::class, 'viewAny']);
        Gate::define('create teachers', [TeacherPolicy::class, 'create']);
        Gate::define('edit teachers', [TeacherPolicy::class, 'update']);
        Gate::define('delete teachers', [TeacherPolicy::class, 'delete']);
    }
}

<?php

namespace App\Providers;
namespace App\Providers\Gate;

use App\Models\Teacher;
use App\Policies\TeacherPolicy;
use App\Models\User;
use App\Models\Report;
use App\Policies\UserPolicy;
use App\Policies\ReportPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Teacher::class => TeacherPolicy::class,
        Report::class => ReportPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('access-users', function ($user) {
            return $user->hasRole('admin');
        });
    }
}

<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Dedication;
use App\Models\PermissionTeacher;
use App\Models\Report;
use App\Models\Site;
use App\Models\Teacher;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\DedicationPolicy;
use App\Policies\PermissionTeacherPolicy;
use App\Policies\ReportPolicy;
use App\Policies\SitePolicy;
use App\Policies\TeacherPolicy;
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
        User::class => UserPolicy::class,
        Teacher::class => TeacherPolicy::class,
        Report::class => ReportPolicy::class,
        Category::class => CategoryPolicy::class,
        Dedication::class => DedicationPolicy::class,
        PermissionTeacher::class => PermissionTeacherPolicy::class,
        Site::class => SitePolicy::class,
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

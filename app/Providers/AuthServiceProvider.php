<?php

namespace App\Providers;

use App\Policies\ProjectRegionPolicy;
use App\Policies\QueryGrouptPolicy;
use App\ProjectRegion;
use App\QueryGroup;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        ProjectRegion::class => ProjectRegionPolicy::class,
        QueryGroup::class => QueryGrouptPolicy::class
       // 'App\ProjectRegion' => 'App\Policies\ProjectRegionPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

//        Gate::define('create', 'App\Policies\ProjectRegionPolicy@create');
    }
}

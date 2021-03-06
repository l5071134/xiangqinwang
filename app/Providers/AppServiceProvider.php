<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//         $this->app->singleton(
//         \App\Konohanaruto\Repositories\Intranet\User\UserRepositoryInterface::class,
//         \App\Konohanaruto\Repositories\Intranet\User\UserEloquentRepository::class
//         );
        $this->app->bind(
            \App\Konohanaruto\Repositories\Intranet\User\UserRepositoryInterface::class,
            \App\Konohanaruto\Repositories\Intranet\User\UserEloquentRepository::class
        );
        
        // privileges
        $this->app->singleton(
        \App\Konohanaruto\Repositories\Intranet\Permission\PermissionRepositoryInterface::class,
        \App\Konohanaruto\Repositories\Intranet\Permission\PermissionEloquentRepository::class
        );
        
        // Role
        $this->app->singleton(
            \App\Konohanaruto\Repositories\Intranet\Role\RoleRepositoryInterface::class,
            \App\Konohanaruto\Repositories\Intranet\Role\RoleEloquentRepository::class
        );
        
        // RolePermission
        $this->app->singleton(
            \App\Konohanaruto\Repositories\Intranet\RolePermission\RolePermissionRepositoryInterface::class,
            \App\Konohanaruto\Repositories\Intranet\RolePermission\RolePermissionEloquentRepository::class
        );
    }
}

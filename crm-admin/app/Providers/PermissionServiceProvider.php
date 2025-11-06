<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $user = Auth::user();
            $permissions = [];

            if ($user && $user->role) {
                $permissions = $user->role->modulePermissions->keyBy('module_name');
            }
            
            // $view->with('permissions', $permissions);
            $view->with([
                'user' => $user,
                'permissions' => $permissions,
            ]);
        });
    }
}

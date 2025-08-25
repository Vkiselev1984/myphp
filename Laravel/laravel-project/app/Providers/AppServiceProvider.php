<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\News;
use App\Observers\NewsObserver;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (class_exists(News::class)) {
            News::observe(NewsObserver::class);
        }

        // Ensure API & Web routes are loaded in all environments (including tests)
        \Illuminate\Support\Facades\Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        \Illuminate\Support\Facades\Route::middleware('web')
            ->group(base_path('routes/web.php'));

        // Share auth user and admin flag with all Twig views
        View::composer('*', function ($view) {
            $user = auth()->user();
            $view->with('current_user', $user);
            $view->with('is_admin', $user ? (bool) ($user->is_admin ?? false) : false);
        });
    }
}

<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\LorePage;
use App\Models\Novella;
use App\Policies\LorePagePolicy;
use App\Policies\NovellaPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Ensure debugbar is disabled in production
        // (It's in require-dev so won't be installed, but this is extra safety)
        if ($this->app->environment('production')) {
            $this->app['config']->set('debugbar.enabled', false);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(LorePage::class, LorePagePolicy::class);
        Gate::policy(Novella::class, NovellaPolicy::class);

        Gate::define('access-lore', function ($user) {
            return $user && $user->isContributor();
        });

        Gate::define('admin', function ($user) {
            return $user && $user->isAdmin();
        });
    }
}

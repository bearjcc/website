<?php

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
        //
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

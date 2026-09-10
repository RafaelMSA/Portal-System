<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\UrlGenerator;
use App\Models\Announcement;
use App\Models\Achievement;
use App\Models\User;
use App\Observers\AnnouncementObserver;
use App\Observers\AchievementObserver;
use App\Policies\AnnouncementPolicy;
use App\Policies\AchievementPolicy;
use App\Policies\ProfilePolicy;

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
    public function boot(UrlGenerator $url): void
    {
        if (env('APP_ENV') === 'production') {
            $url->forceScheme('https');
        }

        Schema::defaultStringLength(191);

        Announcement::observe(AnnouncementObserver::class);
        Achievement::observe(AchievementObserver::class);

        // Register authorization policies
        Gate::policy(Announcement::class, AnnouncementPolicy::class);
        Gate::policy(Achievement::class, AchievementPolicy::class);
        Gate::policy(User::class, ProfilePolicy::class);
    }
}

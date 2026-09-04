<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\ClientLogo;
use App\Models\Consultation;
use App\Models\Media;
use App\Models\Post;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Policies\AdminPolicy;
use App\Policies\ClientLogoPolicy;
use App\Policies\ConsultationPolicy;
use App\Policies\MediaPolicy;
use App\Policies\PostPolicy;
use App\Policies\ProjectCategoryPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\ServicePolicy;
use App\Policies\SiteSettingPolicy;
use App\Policies\TestimonialPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        /*
        |--------------------------------------------------------------------------
        | Admin Login Rate Limiting
        |--------------------------------------------------------------------------
        |
        | Limit admin login attempts to 5 requests per minute
        | per email address and IP address combination.
        |
        */

        RateLimiter::for('admin-login', function (Request $request) {
            $email = Str::lower(
                Str::trim(
                    (string) $request->input('email')
                )
            );

            return Limit::perMinute(5)
                ->by(
                    $email.'|'.$request->ip()
                );
        });

        /*
        |--------------------------------------------------------------------------
        | Policies
        |--------------------------------------------------------------------------
        |
        | Explicitly register all Admin API policies.
        |
        */

        Gate::policy(
            Admin::class,
            AdminPolicy::class
        );

        Gate::policy(
            Media::class,
            MediaPolicy::class
        );

        Gate::policy(
            Service::class,
            ServicePolicy::class
        );

        Gate::policy(
            Project::class,
            ProjectPolicy::class
        );

        Gate::policy(
            ProjectCategory::class,
            ProjectCategoryPolicy::class
        );

        Gate::policy(
            Post::class,
            PostPolicy::class
        );

        Gate::policy(
            ClientLogo::class,
            ClientLogoPolicy::class
        );

        Gate::policy(
            Testimonial::class,
            TestimonialPolicy::class
        );

        Gate::policy(
            Consultation::class,
            ConsultationPolicy::class
        );

        Gate::policy(
            SiteSetting::class,
            SiteSettingPolicy::class
        );
    }
}

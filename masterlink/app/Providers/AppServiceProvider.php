<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Policies\PostPolicy;
use App\Policies\ProjectCategoryPolicy;
use App\Policies\ProjectPolicy;
use App\Models\Consultation;
use App\Policies\ConsultationPolicy;
use App\Models\ClientLogo;
use App\Policies\ClientLogoPolicy;
use App\Models\Testimonial;
use App\Policies\TestimonialPolicy;
use App\Models\SiteSetting;
use App\Policies\SiteSettingPolicy;
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
        Gate::policy(
            Project::class,
            ProjectPolicy::class
        );
        Gate::policy(
            SiteSetting::class,
            SiteSettingPolicy::class
        );


        Gate::policy(
            Testimonial::class,
            TestimonialPolicy::class
        );
        Gate::policy(
            ClientLogo::class,
            ClientLogoPolicy::class
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
            Consultation::class,
            ConsultationPolicy::class
        );
    }
}

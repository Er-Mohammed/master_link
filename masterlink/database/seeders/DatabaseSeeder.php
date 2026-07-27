<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Admin;
use App\Models\Media;
use App\Models\Service;
use App\Models\ProjectCategory;
use App\Models\Project;
use App\Models\Post;
use App\Models\Consultation;
use App\Models\ClientLogo;
use App\Models\SiteSetting;
use App\Models\ProjectMedia;
use App\Models\Testimonial;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        /*
        |--------------------------------------------------------------------------
        | Admins
        |--------------------------------------------------------------------------
        */

        Admin::factory(3)->create();


        /*
        |--------------------------------------------------------------------------
        | Media
        |--------------------------------------------------------------------------
        */

        Media::factory(30)->create();


        /*
        |--------------------------------------------------------------------------
        | Services
        |--------------------------------------------------------------------------
        */

        Service::factory(6)->create();


        /*
        |--------------------------------------------------------------------------
        | Service Media
        |--------------------------------------------------------------------------
        */

        Service::all()->each(function ($service) {

            $mediaIds = Media::inRandomOrder()
                ->limit(3)
                ->pluck('id');


            foreach ($mediaIds as $mediaId) {

                $service->media()->attach($mediaId, [

                    'sort_order' => fake()->numberBetween(0,10)

                ]);

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Site Settings
        |--------------------------------------------------------------------------
        */

        SiteSetting::factory(10)->create();


        /*
        |--------------------------------------------------------------------------
        | Project Categories
        |--------------------------------------------------------------------------
        */

        ProjectCategory::factory(5)->create();


        /*
        |--------------------------------------------------------------------------
        | Projects
        |--------------------------------------------------------------------------
        */

        Project::factory(15)->create();



        /*
        |--------------------------------------------------------------------------
        | Project Media
        |--------------------------------------------------------------------------
        */

        Project::all()->each(function ($project) {


            $mediaIds = Media::inRandomOrder()
                ->limit(4)
                ->pluck('id');


            foreach ($mediaIds as $mediaId) {


                ProjectMedia::create([

                    'project_id' => $project->id,

                    'media_id' => $mediaId,

                    'sort_order' => fake()->numberBetween(0,10),

                ]);


            }


        });



        /*
        |--------------------------------------------------------------------------
        | Project Services
        |--------------------------------------------------------------------------
        */

        Project::all()->each(function ($project) {


            $serviceIds = Service::inRandomOrder()
                ->limit(3)
                ->pluck('id');


            $project->services()
                ->attach($serviceIds);


        });



        /*
        |--------------------------------------------------------------------------
        | Client Logos
        |--------------------------------------------------------------------------
        */

        Media::whereDoesntHave('clientLogo')
            ->inRandomOrder()
            ->limit(8)
            ->get()
            ->each(function ($media) {


                ClientLogo::factory()->create([

                    'media_id' => $media->id

                ]);


            });

/*
|--------------------------------------------------------------------------
| Testimonials
|--------------------------------------------------------------------------
*/

Media::inRandomOrder()
    ->take(6)
    ->get()
    ->each(function ($media) {

        Testimonial::factory()->create([

            'media_id' => $media->id,

        ]);

    });

        /*
        |--------------------------------------------------------------------------
        | Posts
        |--------------------------------------------------------------------------
        */

        Post::factory(10)->create();



        /*
        |--------------------------------------------------------------------------
        | Consultations
        |--------------------------------------------------------------------------
        */

        Consultation::factory(20)->create();

    }
}
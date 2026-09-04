<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceMediaFactory extends Factory
{
    public function definition(): array
    {

        return [

            'service_id' => Service::factory(),

            'media_id' => Media::factory(),

            'sort_order' => fake()
                ->numberBetween(0, 10),

        ];

    }
}

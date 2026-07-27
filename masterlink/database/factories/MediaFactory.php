<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    public function definition(): array
    {
        $extension = fake()->randomElement([
            'jpg',
            'png',
            'webp',
            'mp4'
        ]);

        $mediaType = match ($extension) {

            'mp4' => 'video',

            default => 'image',

        };


        return [

            'admin_id' => Admin::query()
                ->inRandomOrder()
                ->value('id'),


            'file_name' => fake()->word() . '.' . $extension,


            'file_path' => 'media/' . fake()->uuid() . '.' . $extension,


            'extension' => $extension,


            'media_type' => $mediaType,


            'mime_type' => match ($extension) {

                'mp4' => 'video/mp4',

                'png' => 'image/png',

                'webp' => 'image/webp',

                default => 'image/jpeg',

            },


            'file_size' => fake()
                ->numberBetween(10000, 5000000),


            'width' => fake()
                ->randomElement([800, 1024, 1920]),


            'height' => fake()
                ->randomElement([600, 720, 1080]),


            'alt_text' => fake()->sentence(),

        ];
    }
}
<?php

use App\Models\Testimonial;

it('returns only active testimonials via public api endpoint', function () {
    $active = Testimonial::factory()->create([
        'display_name' => 'Active Client',
        'message' => 'Great work',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $inactive = Testimonial::factory()->create([
        'display_name' => 'Inactive Client',
        'message' => 'Hidden work',
        'is_active' => false,
        'sort_order' => 2,
    ]);

    $response = $this->getJson('/api/testimonials');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'display_name' => 'Active Client',
        ])
        ->assertJsonMissing([
            'display_name' => 'Inactive Client',
        ]);

    $active->delete();
    $inactive->delete();
});

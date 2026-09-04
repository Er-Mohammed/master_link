<?php

use App\Models\Admin;
use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    Storage::fake('public');
});

test('media image upload creates database record and saves file to public disk', function () {
    $admin = Admin::factory()->create([
        'role' => Admin::ROLE_ADMIN,
        'is_active' => true,
    ]);
    Sanctum::actingAs($admin, ['*']);

    $file = UploadedFile::fake()->image('test_hero_image.png', 800, 600);

    $response = $this->postJson('/api/admin/media', [
        'file' => $file,
        'alt_text' => 'Test Image Hero Alt Text',
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.file_name', 'test_hero_image.png')
        ->assertJsonPath('data.media_type', 'image')
        ->assertJsonPath('data.alt_text', 'Test Image Hero Alt Text');

    $mediaId = $response->json('data.id');
    $filePath = $response->json('data.file_path');

    // 1. Verify DB persistence
    $this->assertDatabaseHas('media', [
        'id' => $mediaId,
        'file_name' => 'test_hero_image.png',
        'media_type' => 'image',
    ]);

    // 2. Verify Storage disk persistence
    Storage::disk('public')->assertExists($filePath);

    // 3. Verify GET API retrieves the exact uploaded media
    $listResponse = $this->getJson('/api/admin/media');
    $listResponse->assertOk()
        ->assertJsonFragment([
            'id' => $mediaId,
            'file_name' => 'test_hero_image.png',
        ]);
});

test('media video upload creates database record and saves file to public disk', function () {
    $admin = Admin::factory()->create([
        'role' => Admin::ROLE_ADMIN,
        'is_active' => true,
    ]);
    Sanctum::actingAs($admin, ['*']);

    $file = UploadedFile::fake()->create('promo_video.mp4', 2048, 'video/mp4');

    $response = $this->postJson('/api/admin/media', [
        'file' => $file,
        'alt_text' => 'Promo Video Test Alt',
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.file_name', 'promo_video.mp4')
        ->assertJsonPath('data.media_type', 'video');

    $mediaId = $response->json('data.id');
    $filePath = $response->json('data.file_path');

    $this->assertDatabaseHas('media', [
        'id' => $mediaId,
        'media_type' => 'video',
    ]);

    Storage::disk('public')->assertExists($filePath);

    $listResponse = $this->getJson('/api/admin/media');
    $listResponse->assertOk()
        ->assertJsonFragment([
            'id' => $mediaId,
            'media_type' => 'video',
        ]);
});

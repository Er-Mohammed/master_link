<?php

use App\Models\Admin;
use App\Models\SiteSetting;
use Laravel\Sanctum\Sanctum;

test('public endpoint returns site settings without authentication', function () {
    SiteSetting::query()->create([
        'key' => 'site_logo',
        'value' => 'http://127.0.0.1:8000/storage/media/test_logo.png',
        'type' => 'image',
        'group_name' => 'general',
    ]);

    $response = $this->getJson('/api/site-settings');

    $response->assertOk()
        ->assertJsonFragment([
            'key' => 'site_logo',
            'value' => 'http://127.0.0.1:8000/storage/media/test_logo.png',
        ]);
});

test('super admin can create and update site settings in database', function () {
    $superAdmin = Admin::factory()->create([
        'role' => Admin::ROLE_SUPER_ADMIN,
        'is_active' => true,
    ]);
    Sanctum::actingAs($superAdmin, ['*']);

    // 1. Create site logo setting
    $createResponse = $this->postJson('/api/admin/site-settings', [
        'key' => 'site_logo',
        'value' => 'http://127.0.0.1:8000/storage/media/initial_logo.png',
        'type' => 'image',
        'group_name' => 'general',
    ]);

    $createResponse->assertCreated()
        ->assertJsonPath('data.key', 'site_logo')
        ->assertJsonPath('data.value', 'http://127.0.0.1:8000/storage/media/initial_logo.png');

    $settingId = $createResponse->json('data.id');

    $this->assertDatabaseHas('site_settings', [
        'id' => $settingId,
        'key' => 'site_logo',
        'value' => 'http://127.0.0.1:8000/storage/media/initial_logo.png',
    ]);

    // 2. Update site logo setting
    $updateResponse = $this->putJson("/api/admin/site-settings/{$settingId}", [
        'key' => 'site_logo',
        'value' => 'http://127.0.0.1:8000/storage/media/updated_logo.png',
        'type' => 'image',
        'group_name' => 'general',
    ]);

    $updateResponse->assertOk()
        ->assertJsonPath('data.value', 'http://127.0.0.1:8000/storage/media/updated_logo.png');

    $this->assertDatabaseHas('site_settings', [
        'id' => $settingId,
        'value' => 'http://127.0.0.1:8000/storage/media/updated_logo.png',
    ]);

    // 3. Verify public endpoint reflects updated value immediately
    $publicResponse = $this->getJson('/api/site-settings');
    $publicResponse->assertOk()
        ->assertJsonFragment([
            'key' => 'site_logo',
            'value' => 'http://127.0.0.1:8000/storage/media/updated_logo.png',
        ]);
});

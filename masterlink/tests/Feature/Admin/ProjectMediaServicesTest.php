<?php

use App\Models\Admin;
use App\Models\Media;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->category = ProjectCategory::factory()->create();
});

test('unauthenticated users cannot sync project media or services', function () {
    $project = Project::factory()->create(['category_id' => $this->category->id]);

    $this->putJson("/api/admin/projects/{$project->id}/media", ['media' => []])
        ->assertUnauthorized();

    $this->putJson("/api/admin/projects/{$project->id}/services", ['services' => []])
        ->assertUnauthorized();
});

test('users without authorized role cannot sync project media or services', function () {
    $unauthorizedAdmin = Admin::factory()->create([
        'role' => Admin::ROLE_CONTENT_MANAGER,
        'is_active' => true,
    ]);
    Sanctum::actingAs($unauthorizedAdmin, ['*']);

    $project = Project::factory()->create(['category_id' => $this->category->id]);

    $this->putJson("/api/admin/projects/{$project->id}/media", ['media' => []])
        ->assertForbidden();

    $this->putJson("/api/admin/projects/{$project->id}/services", ['services' => []])
        ->assertForbidden();
});

test('authorized admin can sync project media with list of IDs', function () {
    $admin = Admin::factory()->create([
        'role' => Admin::ROLE_ADMIN,
        'is_active' => true,
    ]);
    Sanctum::actingAs($admin, ['*']);

    $project = Project::factory()->create(['category_id' => $this->category->id]);
    $mediaItems = Media::factory()->count(3)->create();
    $mediaIds = $mediaItems->pluck('id')->toArray();

    $response = $this->putJson("/api/admin/projects/{$project->id}/media", [
        'media' => $mediaIds,
    ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Project media synchronized successfully.',
        ])
        ->assertJsonPath('data.id', $project->id)
        ->assertJsonCount(3, 'data.media')
        ->assertJsonPath('data.media_count', 3);

    expect($project->fresh()->media)->toHaveCount(3);
});

test('authorized admin can sync project media with object items and sort orders', function () {
    $admin = Admin::factory()->create([
        'role' => Admin::ROLE_MARKETING,
        'is_active' => true,
    ]);
    Sanctum::actingAs($admin, ['*']);

    $project = Project::factory()->create(['category_id' => $this->category->id]);
    $media1 = Media::factory()->create();
    $media2 = Media::factory()->create();

    $payload = [
        'media' => [
            ['id' => $media1->id, 'sort_order' => 10],
            ['id' => $media2->id, 'sort_order' => 5],
        ],
    ];

    $response = $this->putJson("/api/admin/projects/{$project->id}/media", $payload);

    $response->assertOk()
        ->assertJsonCount(2, 'data.media');

    $syncedMedia = $project->fresh()->media;
    expect($syncedMedia)->toHaveCount(2);
});

test('media synchronization replaces existing project media', function () {
    $admin = Admin::factory()->create([
        'role' => Admin::ROLE_SUPER_ADMIN,
        'is_active' => true,
    ]);
    Sanctum::actingAs($admin, ['*']);

    $project = Project::factory()->create(['category_id' => $this->category->id]);
    $oldMedia = Media::factory()->create();
    $newMedia = Media::factory()->create();

    $project->media()->attach($oldMedia->id, ['sort_order' => 0]);
    expect($project->fresh()->media)->toHaveCount(1);

    $response = $this->putJson("/api/admin/projects/{$project->id}/media", [
        'media' => [$newMedia->id],
    ]);

    $response->assertOk()
        ->assertJsonCount(1, 'data.media')
        ->assertJsonPath('data.media.0.id', $newMedia->id);

    expect($project->fresh()->media->pluck('id')->toArray())
        ->toBe([$newMedia->id]);
});

test('media synchronization fails validation on invalid media id', function () {
    $admin = Admin::factory()->create([
        'role' => Admin::ROLE_ADMIN,
        'is_active' => true,
    ]);
    Sanctum::actingAs($admin, ['*']);

    $project = Project::factory()->create(['category_id' => $this->category->id]);

    $response = $this->putJson("/api/admin/projects/{$project->id}/media", [
        'media' => [99999],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['media.0']);
});

test('authorized admin can sync project services', function () {
    $admin = Admin::factory()->create([
        'role' => Admin::ROLE_ADMIN,
        'is_active' => true,
    ]);
    Sanctum::actingAs($admin, ['*']);

    $project = Project::factory()->create(['category_id' => $this->category->id]);
    $services = Service::factory()->count(2)->create();
    $serviceIds = $services->pluck('id')->toArray();

    $response = $this->putJson("/api/admin/projects/{$project->id}/services", [
        'services' => $serviceIds,
    ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Project services synchronized successfully.',
        ])
        ->assertJsonPath('data.id', $project->id)
        ->assertJsonCount(2, 'data.services')
        ->assertJsonPath('data.services_count', 2);

    expect($project->fresh()->services)->toHaveCount(2);
});

test('services synchronization replaces existing project services', function () {
    $admin = Admin::factory()->create([
        'role' => Admin::ROLE_SUPER_ADMIN,
        'is_active' => true,
    ]);
    Sanctum::actingAs($admin, ['*']);

    $project = Project::factory()->create(['category_id' => $this->category->id]);
    $oldService = Service::factory()->create();
    $newService = Service::factory()->create();

    $project->services()->attach($oldService->id);
    expect($project->fresh()->services)->toHaveCount(1);

    $response = $this->putJson("/api/admin/projects/{$project->id}/services", [
        'services' => [$newService->id],
    ]);

    $response->assertOk()
        ->assertJsonCount(1, 'data.services')
        ->assertJsonPath('data.services.0.id', $newService->id);

    expect($project->fresh()->services->pluck('id')->toArray())
        ->toBe([$newService->id]);
});

test('services synchronization fails validation on invalid service id', function () {
    $admin = Admin::factory()->create([
        'role' => Admin::ROLE_ADMIN,
        'is_active' => true,
    ]);
    Sanctum::actingAs($admin, ['*']);

    $project = Project::factory()->create(['category_id' => $this->category->id]);

    $response = $this->putJson("/api/admin/projects/{$project->id}/services", [
        'services' => [99999],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['services.0']);
});

test('existing project CRUD endpoints continue to work as expected', function () {
    $admin = Admin::factory()->create([
        'role' => Admin::ROLE_ADMIN,
        'is_active' => true,
    ]);
    Sanctum::actingAs($admin, ['*']);

    // 1. GET /api/admin/projects
    $this->getJson('/api/admin/projects')->assertOk();

    // 2. POST /api/admin/projects
    $createResponse = $this->postJson('/api/admin/projects', [
        'category_id' => $this->category->id,
        'title' => 'مشروع اختبار جديد',
        'slug' => 'new-test-project',
        'client_name' => 'عميل اختبار',
        'short_description' => 'وصف قصير',
        'full_description' => 'وصف كامل للمشروع',
        'is_active' => true,
    ]);
    $createResponse->assertCreated();
    $projectId = $createResponse->json('data.id');

    // 3. GET /api/admin/projects/{project}
    $this->getJson("/api/admin/projects/{$projectId}")->assertOk();

    // 4. PUT /api/admin/projects/{project}
    $this->putJson("/api/admin/projects/{$projectId}", [
        'title' => 'مشروع اختبار محدث',
    ])->assertOk();

    // 5. DELETE /api/admin/projects/{project}
    $this->deleteJson("/api/admin/projects/{$projectId}")->assertOk();

    // 6. GET /api/admin/project-categories
    $this->getJson('/api/admin/project-categories')->assertOk();
});

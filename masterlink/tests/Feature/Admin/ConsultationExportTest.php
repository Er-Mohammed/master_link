<?php

use App\Models\Admin;
use App\Models\Consultation;
use App\Models\Service;
use Laravel\Sanctum\Sanctum;

test('unauthenticated users cannot export consultations to excel or pdf', function () {
    $this->getJson('/api/admin/consultations/export/excel')
        ->assertUnauthorized();

    $this->getJson('/api/admin/consultations/export/pdf')
        ->assertUnauthorized();
});

test('content_manager role cannot export consultations', function () {
    $contentManager = Admin::factory()->create([
        'role' => Admin::ROLE_CONTENT_MANAGER,
        'is_active' => true,
    ]);
    Sanctum::actingAs($contentManager, ['*']);

    $this->getJson('/api/admin/consultations/export/excel')
        ->assertForbidden();

    $this->getJson('/api/admin/consultations/export/pdf')
        ->assertForbidden();
});

test('authorized admin roles (super_admin, admin, marketing) can export consultations to excel', function ($role) {
    $admin = Admin::factory()->create([
        'role' => $role,
        'is_active' => true,
    ]);
    Sanctum::actingAs($admin, ['*']);

    $service = Service::factory()->create();
    Consultation::factory()->count(3)->create(['service_id' => $service->id]);

    $response = $this->get('/api/admin/consultations/export/excel');

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    expect($response->getContent())->not()->toBeEmpty();
})->with([
    Admin::ROLE_SUPER_ADMIN,
    Admin::ROLE_ADMIN,
    Admin::ROLE_MARKETING,
]);

test('authorized admin roles (super_admin, admin, marketing) can export consultations to pdf', function ($role) {
    $admin = Admin::factory()->create([
        'role' => $role,
        'is_active' => true,
    ]);
    Sanctum::actingAs($admin, ['*']);

    $service = Service::factory()->create();
    Consultation::factory()->count(2)->create(['service_id' => $service->id]);

    $response = $this->get('/api/admin/consultations/export/pdf');

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/pdf');
    expect($response->getContent())->not()->toBeEmpty();
})->with([
    Admin::ROLE_SUPER_ADMIN,
    Admin::ROLE_ADMIN,
    Admin::ROLE_MARKETING,
]);

test('export excel and pdf endpoints accept status and search query filters', function () {
    $admin = Admin::factory()->create([
        'role' => Admin::ROLE_SUPER_ADMIN,
        'is_active' => true,
    ]);
    Sanctum::actingAs($admin, ['*']);

    Consultation::factory()->create([
        'name' => 'شركة الأفق الممتازة',
        'status' => 'new',
    ]);
    Consultation::factory()->create([
        'name' => 'شركة البناء المتقدم',
        'status' => 'completed',
    ]);

    // Excel with search & status filter
    $excelResp = $this->get('/api/admin/consultations/export/excel?search=الأفق&status=new');
    $excelResp->assertOk();
    $excelResp->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    // PDF with search & status filter
    $pdfResp = $this->get('/api/admin/consultations/export/pdf?search=الأفق&status=new');
    $pdfResp->assertOk();
    $pdfResp->assertHeader('Content-Type', 'application/pdf');
});

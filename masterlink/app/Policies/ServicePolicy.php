<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Service;

class ServicePolicy
{
    /**
     * View any services.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
            ]);
    }

    /**
     * View a specific service.
     */
    public function view(Admin $admin, Service $service): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
            ]);
    }

    /**
     * Create a service.
     */
    public function create(Admin $admin): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
            ]);
    }

    /**
     * Update a service.
     */
    public function update(Admin $admin, Service $service): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
            ]);
    }

    /**
     * Delete a service.
     */
    public function delete(Admin $admin, Service $service): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
            ]);
    }
}

<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\ClientLogo;

class ClientLogoPolicy
{
    /**
     * View any client logos.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_MARKETING,
            ]);
    }

    /**
     * View a specific client logo.
     */
    public function view(Admin $admin, ClientLogo $clientLogo): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_MARKETING,
            ]);
    }

    /**
     * Create a client logo.
     */
    public function create(Admin $admin): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_MARKETING,
            ]);
    }

    /**
     * Update a client logo.
     */
    public function update(Admin $admin, ClientLogo $clientLogo): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_MARKETING,
            ]);
    }

    /**
     * Delete a client logo.
     */
    public function delete(Admin $admin, ClientLogo $clientLogo): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_MARKETING,
            ]);
    }

    /**
     * Restore a deleted client logo.
     */
    public function restore(Admin $admin, ClientLogo $clientLogo): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_MARKETING,
            ]);
    }

    /**
     * Permanently delete a client logo.
     */
    public function forceDelete(Admin $admin, ClientLogo $clientLogo): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
            ]);
    }
}
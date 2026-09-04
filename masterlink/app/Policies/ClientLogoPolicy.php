<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\ClientLogo;

class ClientLogoPolicy
{
    /**
     * Determine whether the admin can view any client logos.
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
     * Determine whether the admin can view the client logo.
     */
    public function view(
        Admin $admin,
        ClientLogo $clientLogo
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_MARKETING,
            ]);
    }

    /**
     * Determine whether the admin can create client logos.
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
     * Determine whether the admin can update the client logo.
     */
    public function update(
        Admin $admin,
        ClientLogo $clientLogo
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_MARKETING,
            ]);
    }

    /**
     * Determine whether the admin can delete the client logo.
     */
    public function delete(
        Admin $admin,
        ClientLogo $clientLogo
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_MARKETING,
            ]);
    }
}

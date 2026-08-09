<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\SiteSetting;

class SiteSettingPolicy
{
    /**
     * View any site settings.
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
     * View a specific site setting.
     */
    public function view(Admin $admin, SiteSetting $siteSetting): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
            ]);
    }

    /**
     * Create a site setting.
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
     * Update a site setting.
     */
    public function update(Admin $admin, SiteSetting $siteSetting): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
            ]);
    }

    /**
     * Delete a site setting.
     */
    public function delete(Admin $admin, SiteSetting $siteSetting): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
            ]);
    }

    /**
     * Restore a deleted site setting.
     */
    public function restore(Admin $admin, SiteSetting $siteSetting): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
            ]);
    }

    /**
     * Permanently delete a site setting.
     */
    public function forceDelete(Admin $admin, SiteSetting $siteSetting): bool
    {
        return $admin->isActive()
            && $admin->hasRole(
                Admin::ROLE_SUPER_ADMIN
            );
    }
}
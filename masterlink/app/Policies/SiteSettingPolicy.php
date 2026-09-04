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
            && $admin->isSuperAdmin();
    }

    /**
     * View a specific site setting.
     */
    public function view(
        Admin $admin,
        SiteSetting $siteSetting
    ): bool {
        return $admin->isActive()
            && $admin->isSuperAdmin();
    }

    /**
     * Create a site setting.
     */
    public function create(Admin $admin): bool
    {
        return $admin->isActive()
            && $admin->isSuperAdmin();
    }

    /**
     * Update a site setting.
     */
    public function update(
        Admin $admin,
        SiteSetting $siteSetting
    ): bool {
        return $admin->isActive()
            && $admin->isSuperAdmin();
    }

    /**
     * Delete a site setting.
     */
    public function delete(
        Admin $admin,
        SiteSetting $siteSetting
    ): bool {
        return $admin->isActive()
            && $admin->isSuperAdmin();
    }
}

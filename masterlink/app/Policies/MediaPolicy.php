<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Media;

class MediaPolicy
{
    /**
     * View media list.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_CONTENT_MANAGER,
            ]);
    }

    /**
     * View a specific media item.
     */
    public function view(Admin $admin, Media $media): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_CONTENT_MANAGER,
            ]);
    }

    /**
     * Create media.
     */
    public function create(Admin $admin): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_CONTENT_MANAGER,
            ]);
    }

    /**
     * Update media.
     */
    public function update(Admin $admin, Media $media): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_CONTENT_MANAGER,
            ]);
    }

    /**
     * Soft delete media.
     */
    public function delete(Admin $admin, Media $media): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_CONTENT_MANAGER,
            ]);
    }

    /**
     * Restore soft-deleted media.
     */
    public function restore(Admin $admin, Media $media): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_CONTENT_MANAGER,
            ]);
    }

    /**
     * Permanently delete media.
     */
    public function forceDelete(Admin $admin, Media $media): bool
    {
        return $admin->isActive()
            && $admin->hasRole(Admin::ROLE_SUPER_ADMIN);
    }
}
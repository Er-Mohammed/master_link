<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Media;

class MediaPolicy
{
    /**
     * Determine whether the admin can view any media.
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
     * Determine whether the admin can view the media.
     */
    public function view(
        Admin $admin,
        Media $media
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_CONTENT_MANAGER,
            ]);
    }

    /**
     * Determine whether the admin can create media.
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
     * Determine whether the admin can update the media.
     */
    public function update(
        Admin $admin,
        Media $media
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_CONTENT_MANAGER,
            ]);
    }

    /**
     * Determine whether the admin can delete the media.
     */
    public function delete(
        Admin $admin,
        Media $media
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_CONTENT_MANAGER,
            ]);
    }

    /**
     * Determine whether the admin can restore the media.
     */
    public function restore(
        Admin $admin,
        Media $media
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_CONTENT_MANAGER,
            ]);
    }

    /**
     * Determine whether the admin can permanently delete media.
     */
    public function forceDelete(
        Admin $admin,
        Media $media
    ): bool {
        return $admin->isActive()
            && $admin->isSuperAdmin();
    }
}
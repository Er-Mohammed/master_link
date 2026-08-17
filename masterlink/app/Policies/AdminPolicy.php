<?php

namespace App\Policies;

use App\Models\Admin;

class AdminPolicy
{
    /**
     * View all admins.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->isActive()
            && $admin->isSuperAdmin();
    }

    /**
     * View a specific admin.
     */
    public function view(
        Admin $admin,
        Admin $targetAdmin
    ): bool {
        return $admin->isActive()
            && $admin->isSuperAdmin();
    }

    /**
     * Create an admin.
     */
    public function create(Admin $admin): bool
    {
        return $admin->isActive()
            && $admin->isSuperAdmin();
    }

    /**
     * Update an admin.
     */
    public function update(
        Admin $admin,
        Admin $targetAdmin
    ): bool {
        if (
            ! $admin->isActive()
            || ! $admin->isSuperAdmin()
        ) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Super Admin cannot update himself through Admin Management.
        |--------------------------------------------------------------------------
        */

        if ($admin->id === $targetAdmin->id) {
            return false;
        }

        return true;
    }

    /**
     * Delete an admin.
     */
    public function delete(
        Admin $admin,
        Admin $targetAdmin
    ): bool {
        if (
            ! $admin->isActive()
            || ! $admin->isSuperAdmin()
        ) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Cannot delete yourself.
        |--------------------------------------------------------------------------
        */

        if ($admin->id === $targetAdmin->id) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Cannot delete the last Super Admin.
        |--------------------------------------------------------------------------
        */

        if (
            $targetAdmin->isSuperAdmin()
            && Admin::query()
                ->where(
                    'role',
                    Admin::ROLE_SUPER_ADMIN
                )
                ->where(
                    'id',
                    '!=',
                    $targetAdmin->id
                )
                ->doesntExist()
        ) {
            return false;
        }

        return true;
    }

    /**
     * Restore a deleted admin.
     */
    public function restore(
        Admin $admin,
        Admin $targetAdmin
    ): bool {
        return $admin->isActive()
            && $admin->isSuperAdmin();
    }

    /**
     * Permanently delete an admin.
     */
    public function forceDelete(
        Admin $admin,
        Admin $targetAdmin
    ): bool {
        if (
            ! $admin->isActive()
            || ! $admin->isSuperAdmin()
        ) {
            return false;
        }

        if ($admin->id === $targetAdmin->id) {
            return false;
        }

        if (
            $targetAdmin->isSuperAdmin()
            && Admin::query()
                ->where(
                    'role',
                    Admin::ROLE_SUPER_ADMIN
                )
                ->where(
                    'id',
                    '!=',
                    $targetAdmin->id
                )
                ->doesntExist()
        ) {
            return false;
        }

        return true;
    }
}
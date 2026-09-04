<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\ProjectCategory;

class ProjectCategoryPolicy
{
    /**
     * Determine whether the admin can view any categories.
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
     * Determine whether the admin can view a category.
     */
    public function view(
        Admin $admin,
        ProjectCategory $projectCategory
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
            ]);
    }

    /**
     * Determine whether the admin can create categories.
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
     * Determine whether the admin can update a category.
     */
    public function update(
        Admin $admin,
        ProjectCategory $projectCategory
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
            ]);
    }

    /**
     * Determine whether the admin can delete a category.
     */
    public function delete(
        Admin $admin,
        ProjectCategory $projectCategory
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
            ]);
    }
}

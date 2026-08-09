<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\ProjectCategory;

class ProjectCategoryPolicy
{
    /**
     * View any project categories.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->hasAnyRole([
            Admin::ROLE_SUPER_ADMIN,
            Admin::ROLE_MARKETING,
        ]);
    }

    /**
     * View a specific project category.
     */
    public function view(Admin $admin, ProjectCategory $category): bool
    {
        return $admin->hasAnyRole([
            Admin::ROLE_SUPER_ADMIN,
            Admin::ROLE_MARKETING,
        ]);
    }

    /**
     * Create a project category.
     */
    public function create(Admin $admin): bool
    {
        return $admin->hasAnyRole([
            Admin::ROLE_SUPER_ADMIN,
            Admin::ROLE_MARKETING,
        ]);
    }

    /**
     * Update a project category.
     */
    public function update(
        Admin $admin,
        ProjectCategory $category
    ): bool {
        return $admin->hasAnyRole([
            Admin::ROLE_SUPER_ADMIN,
            Admin::ROLE_MARKETING,
        ]);
    }

    /**
     * Delete a project category.
     */
    public function delete(
        Admin $admin,
        ProjectCategory $category
    ): bool {
        return $admin->hasAnyRole([
            Admin::ROLE_SUPER_ADMIN,
            Admin::ROLE_MARKETING,
        ]);
    }

    /**
     * Restore a soft-deleted project category.
     */
    public function restore(
        Admin $admin,
        ProjectCategory $category
    ): bool {
        return $admin->hasAnyRole([
            Admin::ROLE_SUPER_ADMIN,
            Admin::ROLE_MARKETING,
        ]);
    }

    /**
     * Permanently delete a project category.
     */
    public function forceDelete(
        Admin $admin,
        ProjectCategory $category
    ): bool {
        return $admin->isSuperAdmin();
    }
}
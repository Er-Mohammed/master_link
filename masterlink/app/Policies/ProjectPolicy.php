<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Project;

class ProjectPolicy
{
    /**
     * Determine whether the admin can view any projects.
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
     * Determine whether the admin can view a project.
     */
    public function view(
        Admin $admin,
        Project $project
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_MARKETING,
            ]);
    }

    /**
     * Determine whether the admin can create projects.
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
     * Determine whether the admin can update a project.
     */
    public function update(
        Admin $admin,
        Project $project
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_MARKETING,
            ]);
    }

    /**
     * Determine whether the admin can delete a project.
     */
    public function delete(
        Admin $admin,
        Project $project
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_MARKETING,
            ]);
    }

    /**
     * Determine whether the admin can restore a project.
     */
    public function restore(
        Admin $admin,
        Project $project
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_MARKETING,
            ]);
    }

    /**
     * Determine whether the admin can permanently delete a project.
     */
    public function forceDelete(
        Admin $admin,
        Project $project
    ): bool {
        return $admin->isActive()
            && $admin->isSuperAdmin();
    }
}

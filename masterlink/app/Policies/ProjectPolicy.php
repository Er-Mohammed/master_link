<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Project;

class ProjectPolicy
{
    /**
     * View any projects.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->hasAnyRole([
            Admin::ROLE_SUPER_ADMIN,
            Admin::ROLE_MARKETING,
        ]);
    }

    /**
     * View a specific project.
     */
    public function view(Admin $admin, Project $project): bool
    {
        return $admin->hasAnyRole([
            Admin::ROLE_SUPER_ADMIN,
            Admin::ROLE_MARKETING,
        ]);
    }

    /**
     * Create a project.
     */
    public function create(Admin $admin): bool
    {
        return $admin->hasAnyRole([
            Admin::ROLE_SUPER_ADMIN,
            Admin::ROLE_MARKETING,
        ]);
    }

    /**
     * Update a project.
     */
    public function update(Admin $admin, Project $project): bool
    {
        return $admin->hasAnyRole([
            Admin::ROLE_SUPER_ADMIN,
            Admin::ROLE_MARKETING,
        ]);
    }

    /**
     * Delete a project.
     */
    public function delete(Admin $admin, Project $project): bool
    {
        return $admin->hasAnyRole([
            Admin::ROLE_SUPER_ADMIN,
            Admin::ROLE_MARKETING,
        ]);
    }

    /**
     * Restore a soft-deleted project.
     */
    public function restore(Admin $admin, Project $project): bool
    {
        return $admin->hasAnyRole([
            Admin::ROLE_SUPER_ADMIN,
            Admin::ROLE_MARKETING,
        ]);
    }

    /**
     * Permanently delete a project.
     */
    public function forceDelete(Admin $admin, Project $project): bool
    {
        return $admin->isSuperAdmin();
    }
}
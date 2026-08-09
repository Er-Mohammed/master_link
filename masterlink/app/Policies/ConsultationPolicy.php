<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Consultation;

class ConsultationPolicy
{
    /**
     * View any consultations.
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
     * View a specific consultation.
     */
    public function view(Admin $admin, Consultation $consultation): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_MARKETING,
            ]);
    }

    /**
     * Create a consultation.
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
     * Update a consultation.
     */
    public function update(Admin $admin, Consultation $consultation): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_MARKETING,
            ]);
    }

    /**
     * Delete a consultation.
     */
    public function delete(Admin $admin, Consultation $consultation): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_MARKETING,
            ]);
    }

    /**
     * Restore a deleted consultation.
     */
    public function restore(Admin $admin, Consultation $consultation): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
            ]);
    }

    /**
     * Permanently delete a consultation.
     */
    public function forceDelete(Admin $admin, Consultation $consultation): bool
    {
        return $admin->isActive()
            && $admin->hasRole(Admin::ROLE_SUPER_ADMIN);
    }
}
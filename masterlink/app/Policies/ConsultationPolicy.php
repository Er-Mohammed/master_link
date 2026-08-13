<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Consultation;

class ConsultationPolicy
{
    /**
     * Determine whether the admin can view any consultations.
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
     * Determine whether the admin can view a consultation.
     */
    public function view(
        Admin $admin,
        Consultation $consultation
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_MARKETING,
            ]);
    }

    /**
     * Consultations cannot be created
     * from the admin panel.
     */
    public function create(Admin $admin): bool
    {
        return false;
    }

    /**
     * Determine whether the admin can update
     * a consultation.
     */
    public function update(
        Admin $admin,
        Consultation $consultation
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_MARKETING,
            ]);
    }

    /**
     * Determine whether the admin can delete
     * a consultation.
     */
    public function delete(
        Admin $admin,
        Consultation $consultation
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_MARKETING,
            ]);
    }

    /**
     * Restore is not currently used because
     * Consultation does not use SoftDeletes.
     */
    public function restore(
        Admin $admin,
        Consultation $consultation
    ): bool {
        return false;
    }

    /**
     * Permanently delete a consultation.
     */
    public function forceDelete(
        Admin $admin,
        Consultation $consultation
    ): bool {
        return $admin->isActive()
            && $admin->isSuperAdmin();
    }
}

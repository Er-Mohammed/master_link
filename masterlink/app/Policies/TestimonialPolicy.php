<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Testimonial;

class TestimonialPolicy
{
    /**
     * View any testimonials.
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
     * View a specific testimonial.
     */
    public function view(Admin $admin, Testimonial $testimonial): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_CONTENT_MANAGER,
            ]);
    }

    /**
     * Create a testimonial.
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
     * Update a testimonial.
     */
    public function update(Admin $admin, Testimonial $testimonial): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_CONTENT_MANAGER,
            ]);
    }

    /**
     * Delete a testimonial.
     */
    public function delete(Admin $admin, Testimonial $testimonial): bool
    {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_CONTENT_MANAGER,
            ]);
    }
}

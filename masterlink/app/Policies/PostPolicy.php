<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Post;

class PostPolicy
{
    /**
     * View any posts.
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
     * View a specific post.
     */
    public function view(
        Admin $admin,
        Post $post
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_CONTENT_MANAGER,
            ]);
    }

    /**
     * Create a post.
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
     * Update a post.
     */
    public function update(
        Admin $admin,
        Post $post
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_CONTENT_MANAGER,
            ]);
    }

    /**
     * Delete a post.
     */
    public function delete(
        Admin $admin,
        Post $post
    ): bool {
        return $admin->isActive()
            && $admin->hasAnyRole([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_CONTENT_MANAGER,
            ]);
    }
}

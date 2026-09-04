<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove deleted_at columns if they exist.
     *
     * These columns are already absent from the current
     * database, so this migration is a safety net that
     * runs without error on any environment.
     */
    public function up(): void
    {
        $tables = [
            'admins',
            'media',
            'services',
            'project_categories',
            'projects',
            'posts',
            'client_logos',
            'testimonials',
        ];

        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropColumn('deleted_at');
                });
            }
        }
    }

    public function down(): void
    {
        // No rollback required.
        // SoftDeletes has been permanently removed from the project.
    }
};

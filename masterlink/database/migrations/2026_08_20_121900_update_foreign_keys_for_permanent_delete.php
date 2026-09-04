<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Update foreign key constraints for safe permanent deletion.
     *
     * project_media: cascadeOnDelete (clean pivot on parent delete)
     * posts.media_id: nullOnDelete (keep post, clear media reference)
     * posts.admin_id: nullOnDelete (keep post, clear admin reference)
     * consultations.service_id: nullOnDelete (keep consultation history)
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | project_media — Add cascadeOnDelete
        |--------------------------------------------------------------------------
        */

        Schema::table('project_media', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropForeign(['media_id']);

            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->cascadeOnDelete();

            $table->foreign('media_id')
                ->references('id')
                ->on('media')
                ->cascadeOnDelete();
        });

        /*
        |--------------------------------------------------------------------------
        | posts.media_id — Change to nullOnDelete
        |--------------------------------------------------------------------------
        */

        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['media_id']);

            $table->foreign('media_id')
                ->references('id')
                ->on('media')
                ->nullOnDelete();
        });

        /*
        |--------------------------------------------------------------------------
        | posts.admin_id — Change to nullOnDelete
        |--------------------------------------------------------------------------
        */

        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);

            $table->foreign('admin_id')
                ->references('id')
                ->on('admins')
                ->nullOnDelete();
        });

        /*
        |--------------------------------------------------------------------------
        | consultations.service_id — Change to nullOnDelete
        |--------------------------------------------------------------------------
        */

        Schema::table('consultations', function (Blueprint $table) {
            $table->dropForeign(['service_id']);

            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Restore original foreign key constraints
        |--------------------------------------------------------------------------
        */

        Schema::table('project_media', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropForeign(['media_id']);

            $table->foreign('project_id')
                ->references('id')
                ->on('projects');

            $table->foreign('media_id')
                ->references('id')
                ->on('media');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['media_id']);

            $table->foreign('media_id')
                ->references('id')
                ->on('media');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);

            $table->foreign('admin_id')
                ->references('id')
                ->on('admins');
        });

        Schema::table('consultations', function (Blueprint $table) {
            $table->dropForeign(['service_id']);

            $table->foreign('service_id')
                ->references('id')
                ->on('services');
        });
    }
};

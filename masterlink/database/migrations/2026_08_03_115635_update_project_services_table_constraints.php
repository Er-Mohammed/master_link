<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_services', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Remove old foreign keys
            |--------------------------------------------------------------------------
            */

            $table->dropForeign([
                'project_id'
            ]);

            $table->dropForeign([
                'service_id'
            ]);


            /*
            |--------------------------------------------------------------------------
            | Add Cascade Delete
            |--------------------------------------------------------------------------
            */

            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->cascadeOnDelete();

            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('project_services', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Remove Cascade Foreign Keys
            |--------------------------------------------------------------------------
            */

            $table->dropForeign([
                'project_id'
            ]);

            $table->dropForeign([
                'service_id'
            ]);


            /*
            |--------------------------------------------------------------------------
            | Restore Original Foreign Keys
            |--------------------------------------------------------------------------
            */

            $table->foreign('project_id')
                ->references('id')
                ->on('projects');

            $table->foreign('service_id')
                ->references('id')
                ->on('services');

        });
    }
};
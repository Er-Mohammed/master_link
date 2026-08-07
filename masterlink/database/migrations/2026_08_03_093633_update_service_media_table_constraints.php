<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('service_media', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Remove old foreign keys
            |--------------------------------------------------------------------------
            */

            $table->dropForeign([
                'service_id'
            ]);

            $table->dropForeign([
                'media_id'
            ]);


            /*
            |--------------------------------------------------------------------------
            | Add foreign keys with cascade delete
            |--------------------------------------------------------------------------
            */

            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->cascadeOnDelete();


            $table->foreign('media_id')
                ->references('id')
                ->on('media')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Prevent duplicate media assignment
            |--------------------------------------------------------------------------
            */

          //  $table->unique([
            //    'service_id',
              //  'media_id'
            //]);

        });
    }


    public function down(): void
    {
        Schema::table('service_media', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Remove unique constraint
            |--------------------------------------------------------------------------
            */

            $table->dropUnique([
                'service_id',
                'media_id'
            ]);


            /*
            |--------------------------------------------------------------------------
            | Remove cascade foreign keys
            |--------------------------------------------------------------------------
            */

            $table->dropForeign([
                'service_id'
            ]);

            $table->dropForeign([
                'media_id'
            ]);


            /*
            |--------------------------------------------------------------------------
            | Restore original foreign keys
            |--------------------------------------------------------------------------
            */

            $table->foreign('service_id')
                ->references('id')
                ->on('services');


            $table->foreign('media_id')
                ->references('id')
                ->on('media');

        });
    }
};
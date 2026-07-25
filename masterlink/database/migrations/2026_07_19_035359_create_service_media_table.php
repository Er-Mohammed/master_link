<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_media', function (Blueprint $table) {

            $table->id();

            $table->foreignId('service_id')
                ->constrained('services');

            $table->foreignId('media_id')
                ->constrained('media');

            $table->integer('sort_order')
                ->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_media');
    }
};

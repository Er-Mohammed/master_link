<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {

            $table->id();

            $table->foreignId('admin_id')
                ->nullable()
                ->constrained('admins');

            $table->foreignId('media_id')
                ->nullable()
                ->constrained('media');

            $table->string('title', 200);

            $table->string('slug', 220)
                ->unique();

            $table->text('short_description')
                ->nullable();

            $table->text('content')
                ->nullable();

            $table->dateTime('published_at')
                ->nullable();

            $table->boolean('is_featured')
                ->default(false);

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

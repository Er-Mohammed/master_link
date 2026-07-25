<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {

            $table->id();

            // اسم الخدمة
            $table->string('title', 150);

            // رابط الخدمة
            $table->string('slug', 180)
                ->unique();

            // وصف مختصر
            $table->text('short_description')
                ->nullable();

            // وصف كامل
            $table->text('full_description')
                ->nullable();

            // ترتيب ظهور الخدمات
            $table->integer('sort_order')
                ->default(0);

            // حالة الخدمة (ظهور / إخفاء)
            $table->boolean('is_active')
                ->default(true);

            // created_at + updated_at
            $table->timestamps();

            // الحذف الناعم
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

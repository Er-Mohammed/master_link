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
        Schema::create('testimonials', function (Blueprint $table) {

            $table->id();

            // صورة الشخص أو شعار الشركة (اختياري)
            $table->foreignId('media_id')
                ->nullable()
                ->constrained('media')
                ->nullOnDelete();

            // الاسم الذي سيظهر (شخص أو شركة)
            $table->string('display_name', 150);

            // نص الشهادة
            $table->text('message');

            // ترتيب العرض
            $table->unsignedInteger('sort_order')
                ->default(0);

            // حالة الظهور
            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};

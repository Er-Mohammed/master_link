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
        Schema::create('media', function (Blueprint $table) {

            $table->id();

            // من قام برفع الملف
            $table->foreignId('admin_id')
                ->nullable()
                ->constrained('admins')
                ->nullOnDelete();

            // الاسم الأصلي للملف
            $table->string('file_name', 255);

            // مسار الملف داخل التخزين
            $table->string('file_path', 500);

            // امتداد الملف (jpg, png, pdf, mp4 ...)
            $table->string('extension', 10)->nullable();

            // نوع الوسيط
            $table->enum('media_type', [
                'image',
                'video',
                'document',
            ]);

            // MIME Type
            $table->string('mime_type', 100);

            // حجم الملف بالبايت
            $table->unsignedBigInteger('file_size')->nullable();

            // أبعاد الصورة أو الفيديو
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();

            // نص بديل لتحسين SEO وإمكانية الوصول
            $table->string('alt_text', 255)->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};

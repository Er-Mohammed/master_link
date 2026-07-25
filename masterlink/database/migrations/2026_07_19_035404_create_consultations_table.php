<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {

            $table->id();

            $table->string('name', 150);

            $table->string('email', 150)
                ->nullable();

            $table->string('phone', 50)
                ->nullable();

            $table->string('company_name', 150)
                ->nullable();

            $table->foreignId('service_id')
                ->nullable()
                ->constrained('services');

            $table->text('message')
                ->nullable();

            $table->string('status', 50)
                ->default('new');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};

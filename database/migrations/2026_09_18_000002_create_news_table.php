<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category', 50)->default('Announcement');
            $table->string('badge_label', 50)->nullable()->default('NEW');
            $table->text('summary');
            $table->longText('content')->nullable();
            $table->string('image_url')->nullable()->default('assets/hero-campus.jpg');
            $table->string('author', 100)->default('TTBDO Media Communications');
            $table->string('meeting_focus', 100)->nullable();
            $table->string('startups_supported', 50)->nullable();
            $table->string('coverage_area', 100)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->date('published_date');
            $table->timestamps();

            $table->index(['is_published', 'published_date']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};

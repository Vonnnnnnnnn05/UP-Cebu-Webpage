<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 100);
            $table->string('email', 100);
            $table->string('contact_number', 30)->nullable();
            $table->string('affiliation', 50)->default('general_public');
            $table->string('inquiry_type', 50)->default('general');
            $table->string('subject', 200);
            $table->text('message');
            $table->string('status', 20)->default('pending'); // pending, in_review, resolved, archived
            $table->text('admin_notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('inquiry_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};

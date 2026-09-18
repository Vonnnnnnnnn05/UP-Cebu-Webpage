<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category', 50)->default('Workshop');
            $table->string('badge_label', 50)->nullable()->default('UPCOMING');
            $table->date('event_date');
            $table->string('start_time', 20)->default('09:00 AM');
            $table->string('end_time', 20)->default('05:00 PM');
            $table->string('venue', 150)->default('UP Cebu Campus');
            $table->string('venue_type', 20)->default('in-person'); // in-person, virtual, hybrid
            $table->text('summary');
            $table->longText('description')->nullable();
            $table->string('registration_url')->nullable()->default('#contact');
            $table->boolean('is_featured')->default(false);
            $table->string('status', 20)->default('upcoming'); // upcoming, completed, cancelled
            $table->timestamps();

            $table->index('event_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

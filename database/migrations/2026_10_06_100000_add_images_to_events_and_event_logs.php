<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'image_url')) {
                $table->string('image_url')->nullable()->after('summary');
            }
        });

        Schema::table('event_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('event_logs', 'image_path')) {
                $table->string('image_path')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'image_url')) {
                $table->dropColumn('image_url');
            }
        });

        Schema::table('event_logs', function (Blueprint $table) {
            if (Schema::hasColumn('event_logs', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
    }
};

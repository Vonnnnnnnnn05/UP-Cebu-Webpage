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
        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('news_id')->nullable()->constrained('news')->nullOnDelete()->after('status');
            $table->boolean('enable_view_link')->default(false)->after('news_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['news_id']);
            $table->dropColumn(['news_id', 'enable_view_link']);
        });
    }
};

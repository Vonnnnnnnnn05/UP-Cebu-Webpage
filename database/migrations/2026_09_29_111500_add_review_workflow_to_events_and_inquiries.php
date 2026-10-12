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
            $table->string('approval_status', 30)->default('approved')->after('status');
            $table->text('director_comments')->nullable()->after('approval_status');
            $table->foreignId('reviewed_by')->nullable()->constrained('admins')->nullOnDelete()->after('director_comments');
            $table->foreignId('approved_by')->nullable()->constrained('admins')->nullOnDelete()->after('reviewed_by');
            $table->timestamp('reviewed_at')->nullable()->after('approved_by');
            $table->timestamp('approved_at')->nullable()->after('reviewed_at');
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->text('director_comments')->nullable()->after('admin_notes');
            $table->foreignId('reviewed_by')->nullable()->constrained('admins')->nullOnDelete()->after('director_comments');
            $table->foreignId('approved_by')->nullable()->constrained('admins')->nullOnDelete()->after('reviewed_by');
            $table->timestamp('reviewed_at')->nullable()->after('approved_by');
            $table->timestamp('approved_at')->nullable()->after('reviewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'approval_status',
                'director_comments',
                'reviewed_by',
                'approved_by',
                'reviewed_at',
                'approved_at',
            ]);
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'director_comments',
                'reviewed_by',
                'approved_by',
                'reviewed_at',
                'approved_at',
            ]);
        });
    }
};

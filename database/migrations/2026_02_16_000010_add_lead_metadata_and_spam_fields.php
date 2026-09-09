<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Optional file upload path (your controller already tries to set this)
            if (!Schema::hasColumn('leads', 'attachment_path')) {
                $table->string('attachment_path')->nullable()->after('referrer');
            }

            // Submission metadata (useful for abuse/ops)
            if (!Schema::hasColumn('leads', 'ip_address')) {
                $table->string('ip_address', 45)->nullable()->after('attachment_path');
            }
            if (!Schema::hasColumn('leads', 'user_agent')) {
                $table->string('user_agent', 500)->nullable()->after('ip_address');
            }

            // Spam scoring
            if (!Schema::hasColumn('leads', 'spam_score')) {
                $table->unsignedSmallInteger('spam_score')->default(0)->after('is_spam');
            }
            if (!Schema::hasColumn('leads', 'spam_reasons')) {
                $table->json('spam_reasons')->nullable()->after('spam_score');
            }

            // Helpful indexes
            $table->index(['is_spam', 'spam_score', 'created_at'], 'idx_leads_spam_score_created');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'spam_reasons')) $table->dropColumn('spam_reasons');
            if (Schema::hasColumn('leads', 'spam_score')) $table->dropColumn('spam_score');
            if (Schema::hasColumn('leads', 'user_agent')) $table->dropColumn('user_agent');
            if (Schema::hasColumn('leads', 'ip_address')) $table->dropColumn('ip_address');
            if (Schema::hasColumn('leads', 'attachment_path')) $table->dropColumn('attachment_path');

            $table->dropIndex('idx_leads_spam_score_created');
        });
    }
};

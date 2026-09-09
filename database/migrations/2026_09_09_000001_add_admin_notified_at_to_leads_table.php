<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (! Schema::hasColumn('leads', 'admin_notified_at')) {
                $table->timestamp('admin_notified_at')->nullable()->after('spam_reasons');
            }
        });

        // Backfill: existing non-spam leads were already emailed when they came in.
        DB::table('leads')
            ->whereNull('admin_notified_at')
            ->where('is_spam', false)
            ->update(['admin_notified_at' => DB::raw('created_at')]);
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'admin_notified_at')) {
                $table->dropColumn('admin_notified_at');
            }
        });
    }
};

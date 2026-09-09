<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('redirects')) {
            return;
        }

        Schema::table('redirects', function (Blueprint $table) {
            if (!Schema::hasColumn('redirects', 'last_hit_at')) {
                $table->timestamp('last_hit_at')->nullable()->after('hits');
                $table->index('last_hit_at', 'idx_redirects_last_hit_at');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('redirects')) {
            return;
        }

        Schema::table('redirects', function (Blueprint $table) {
            if (Schema::hasColumn('redirects', 'last_hit_at')) {
                $table->dropIndex('idx_redirects_last_hit_at');
                $table->dropColumn('last_hit_at');
            }
        });
    }
};

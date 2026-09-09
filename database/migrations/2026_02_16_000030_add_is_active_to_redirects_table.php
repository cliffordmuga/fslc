<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('redirects', function (Blueprint $table) {
            if (!Schema::hasColumn('redirects', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('status_code');
                $table->index(['is_active', 'old_path'], 'idx_redirects_active_old');
            }
        });
    }

    public function down(): void
    {
        Schema::table('redirects', function (Blueprint $table) {
            if (Schema::hasColumn('redirects', 'is_active')) {
                $table->dropIndex('idx_redirects_active_old');
                $table->dropColumn('is_active');
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('leads') && ! Schema::hasColumn('leads', 'attachment_original_name')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->string('attachment_original_name', 255)->nullable()->after('attachment_path');
            });
        }

        if (Schema::hasTable('images') && ! Schema::hasColumn('images', 'blur_placeholder')) {
            Schema::table('images', function (Blueprint $table) {
                $table->text('blur_placeholder')->nullable()->after('alt_text');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('leads', 'attachment_original_name')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->dropColumn('attachment_original_name');
            });
        }

        if (Schema::hasColumn('images', 'blur_placeholder')) {
            Schema::table('images', function (Blueprint $table) {
                $table->dropColumn('blur_placeholder');
            });
        }
    }
};

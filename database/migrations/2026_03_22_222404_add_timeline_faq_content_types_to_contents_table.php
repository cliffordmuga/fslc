<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE contents MODIFY COLUMN type ENUM(
                'portfolio', 'services', 'about', 'mission', 'vision', 'intro', 'blog', 'page',
                'timeline_item', 'faq_item'
            ) NOT NULL");
        } else {
            Schema::table('contents', function (Blueprint $table) {
                $table->string('type')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE contents MODIFY COLUMN type ENUM(
                'portfolio', 'services', 'about', 'mission', 'vision', 'intro', 'blog', 'page'
            ) NOT NULL");
        }
    }
};

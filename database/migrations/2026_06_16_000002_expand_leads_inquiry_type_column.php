<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE leads MODIFY inquiry_type VARCHAR(50) NOT NULL DEFAULT 'general'");
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE leads MODIFY inquiry_type ENUM('general','portfolio','service','newsletter') NOT NULL DEFAULT 'general'");
    }
};

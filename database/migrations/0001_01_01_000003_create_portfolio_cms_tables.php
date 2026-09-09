<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Portfolio CMS - Complete Consolidated Database Schema
 *
 * Date: January 24, 2026
 *
 * Goal: refinements that improve performance, scalability, maintainability, security,
 * reliability, load speed, and SEO — while keeping leads minimal, intentional, meaningful.
 *
 * Key refinements included:
 * - ✅ Unique constraint on content_tag (prevents duplicates)
 * - ✅ Better lead attribution/query indexes (dashboard + reporting performance)
 * - ✅ String length caps for UTM/referrer safety & index friendliness
 * - ✅ Computed conversion_rate (REMOVED stored conversion_rate column)
 * - ✅ Improved public analytics table indexing (by content_id/date + date)
 * - ✅ Optional FK integrity improvements (sessions.user_id)
 *
 * Note:
 * - Rate limiting, honeypots, caching fixes, N+1 fixes, and Socialite verification policy
 *   are application-layer changes (middleware/controllers/services), not DB schema.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->createSystemTables();
        $this->createUserTables();
        $this->createContentTables();
        $this->createSeoTables();
        $this->createImageTables();
        $this->createLeadAndAnalyticsTables();
        $this->createTestimonialTables();
        $this->createSettingsTables();
        $this->createActivityLogTables();
    }

    public function down(): void
    {
        $activityConn = config('activitylog.database_connection') ?? config('database.default');
        $activityTable = config('activitylog.table_name') ?? 'activity_log';

        // Drop in reverse creation order to respect foreign key constraints
        Schema::connection($activityConn)->dropIfExists($activityTable);

        Schema::dropIfExists('settings');

        Schema::dropIfExists('content_testimonial');
        Schema::dropIfExists('testimonials');

        Schema::dropIfExists('page_analytics');
        Schema::dropIfExists('ctas');
        Schema::dropIfExists('leads');

        Schema::dropIfExists('images');
        Schema::dropIfExists('seo_metadata');

        Schema::dropIfExists('content_tag');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('contents');

        Schema::dropIfExists('users');

        // Laravel system tables
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
    }

    // =========================================================
    // System Tables
    // =========================================================

    private function createSystemTables(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index(); // FK added after users table exists
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    // =========================================================
    // Users & Auth
    // =========================================================

    private function createUserTables(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            // Password nullable to support OAuth-only users
            $table->string('password')->nullable();

            // OAuth / Social Login
            $table->string('provider', 40)->nullable();
            $table->string('provider_id', 191)->nullable(); // 191 safe for older MySQL index constraints
            $table->string('avatar', 500)->nullable();

            // 2FA (consider encrypting at the model layer via cast)
            $table->string('google2fa_secret')->nullable();
            $table->json('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_verified_at')->nullable();

            $table->enum('role', ['admin', 'user'])->default('user');

            $table->rememberToken();
            $table->timestamps();

            // Indexes
            $table->index(['provider', 'provider_id'], 'idx_users_provider_provider_id');

            // Intentionally NOT indexing google2fa_secret:
            // - rarely queried
            // - conflicts with encrypted-at-rest casts later
        });

        // Add FK now that users table exists
        Schema::table('sessions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    // =========================================================
    // Content
    // =========================================================

    private function createContentTables(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();

            $table->enum('type', [
                'portfolio',
                'services',
                'about',
                'mission',
                'vision',
                'intro',
                'blog',
                'page'
            ]);

            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();

            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();

            $table->integer('sort_order')->default(0);

            $table->foreignId('created_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // Indexes
            $table->index(['type', 'status'], 'idx_contents_type_status');
            $table->index(['status', 'published_at'], 'idx_contents_status_published_at');
            $table->index('sort_order', 'idx_contents_sort_order');

            // Fulltext (MySQL 5.7+/8). Skipped for SQLite (used in tests) which doesn't support it.
            if (DB::connection()->getDriverName() === 'mysql') {
                $table->fullText(['title', 'excerpt', 'content']);
            }
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('content_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            // ✅ Prevent duplicates (priority fix)
            $table->unique(['content_id', 'tag_id'], 'uniq_content_tag');
        });
    }

    // =========================================================
    // SEO / Metadata
    // =========================================================

    private function createSeoTables(): void
    {
        Schema::create('seo_metadata', function (Blueprint $table) {
            $table->id();
            $table->morphs('seoable');

            // Basic meta
            $table->string('meta_title', 60)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->text('meta_keywords')->nullable();

            // Open Graph
            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->string('og_type')->nullable();
            $table->string('og_image', 500)->nullable();

            // Twitter Cards
            $table->string('twitter_card')->nullable();
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image', 500)->nullable();

            // Advanced SEO controls
            $table->json('structured_data')->nullable();
            $table->string('canonical_url', 500)->nullable();
            $table->boolean('noindex')->default(false);
            $table->boolean('nofollow')->default(false);

            // lastmod used for sitemap freshness
            $table->timestamp('lastmod')->nullable();

            $table->timestamps();

            $table->unique(['seoable_type', 'seoable_id'], 'uniq_seoable');
            $table->index('noindex', 'idx_seo_noindex');
            $table->index('nofollow', 'idx_seo_nofollow');
        });
    }

    // =========================================================
    // Images (Polymorphic)
    // =========================================================

    private function createImageTables(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->morphs('imageable');

            $table->string('image_url', 500);

            // variant: main/webp/thumb/etc. (app-level rule: one "main" per collection)
            $table->string('variant', 40)->default('main');

            $table->string('alt_text', 255)->nullable();
            $table->string('collection', 60)->default('default');
            $table->integer('order')->default(0);

            $table->timestamps();

            $table->index(['imageable_type', 'imageable_id', 'collection'], 'idx_images_poly_collection');
            $table->index(['collection', 'variant'], 'idx_images_collection_variant');
        });
    }

    // =========================================================
    // Leads + CTAs + Analytics (computed conversion_rate)
    // =========================================================

    private function createLeadAndAnalyticsTables(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            // Keep this minimal: name/email/message + optional phone
            $table->string('name');
            $table->string('email');
            $table->string('phone', 30)->nullable();

            $table->text('message');

            $table->string('inquiry_type', 50)->default('general');
            $table->foreignId('service_content_id')->nullable()->constrained('contents')->nullOnDelete();

            // Attribution
            $table->foreignId('source_content_id')->nullable()
                ->constrained('contents')
                ->nullOnDelete();

            // Capped lengths to prevent bloat
            $table->string('utm_source', 120)->nullable();
            $table->string('utm_medium', 120)->nullable();
            $table->string('utm_campaign', 120)->nullable();
            $table->string('referrer', 500)->nullable();

            // Pipeline & spam
            $table->enum('status', ['new', 'contacted', 'qualified', 'converted', 'lost'])
                ->default('new');

            $table->boolean('is_spam')->default(false);
            $table->decimal('conversion_value', 10, 2)->nullable();

            $table->timestamps();

            // Indexes (dashboard + reporting)
            $table->index(['status', 'created_at'], 'idx_leads_status_created');
            $table->index(['email', 'inquiry_type'], 'idx_leads_email_inquiry');
            $table->index('utm_source', 'idx_leads_utm_source');
            $table->index(['created_at', 'is_spam'], 'idx_leads_created_spam');



            $table->index(['service_content_id', 'created_at'], 'idx_leads_service_created');

            // ✅ High ROI attribution indexes
            $table->index(['source_content_id', 'created_at'], 'idx_leads_source_created');
            $table->index(['source_content_id', 'status', 'created_at'], 'idx_leads_source_status_created');
        });

        Schema::create('ctas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('content_id')->nullable()
                ->constrained('contents')
                ->cascadeOnDelete();

            $table->string('text');
            $table->string('type', 60);
            $table->string('action', 500);

            // counters
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('conversions')->default(0);

            $table->integer('priority')->default(0);

            $table->timestamps();

            $table->index(['content_id', 'type'], 'idx_ctas_content_type');
            $table->index(['type', 'priority'], 'idx_ctas_type_priority');
        });

        Schema::create('page_analytics', function (Blueprint $table) {
            $table->id();

            $table->foreignId('content_id')->nullable()
                ->constrained('contents')
                ->nullOnDelete();

            $table->date('date');

            $table->integer('views')->default(0);
            $table->integer('unique_visitors')->default(0);
            $table->integer('cta_clicks')->default(0);
            $table->integer('leads_generated')->default(0);

            /**
             * ✅ Computed conversion_rate (NOT stored)
             * Compute as:
             *   conversion_rate = views > 0 ? (leads_generated / views) * 100 : 0
             * at query time, accessor, or view-model layer.
             */

            $table->timestamps();

            // One row per page per day
            $table->unique(['content_id', 'date'], 'uniq_page_analytics_content_date');

            // Performance indexes
            $table->index('date', 'idx_page_analytics_date');
            $table->index(['content_id', 'date'], 'idx_page_analytics_content_date');
        });
    }

    // =========================================================
    // Testimonials
    // =========================================================

    private function createTestimonialTables(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();

            $table->string('client_name');
            $table->text('testimonial');

            $table->tinyInteger('rating')->default(5);
            $table->boolean('is_featured')->default(false);

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();

            $table->index(['status', 'is_featured'], 'idx_testimonials_status_featured');
        });

        Schema::create('content_testimonial', function (Blueprint $table) {
            $table->id();

            $table->foreignId('content_id')->constrained()->cascadeOnDelete();
            $table->foreignId('testimonial_id')->constrained()->cascadeOnDelete();

            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->unique(['content_id', 'testimonial_id'], 'uniq_content_testimonial');
            $table->index(['content_id', 'sort_order'], 'idx_content_testimonial_sort');
        });
    }

    // =========================================================
    // Settings
    // =========================================================

    private function createSettingsTables(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->string('key')->unique();
            $table->text('value')->nullable();

            $table->string('type', 30)->default('text');
            $table->string('category', 60)->default('general');

            $table->text('description')->nullable();

            $table->timestamps();

            $table->index('category', 'idx_settings_category');
        });
    }

    // =========================================================
    // Activity Log (Spatie)
    // =========================================================

    private function createActivityLogTables(): void
    {
        $activityConn  = config('activitylog.database_connection') ?? config('database.default');
        $activityTable = config('activitylog.table_name') ?? 'activity_log';

        Schema::connection($activityConn)->create($activityTable, function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('log_name')->nullable();
            $table->text('description');

            $table->nullableMorphs('subject', 'subject');
            $table->string('event')->nullable();

            $table->nullableMorphs('causer', 'causer');

            $table->json('properties')->nullable();
            $table->uuid('batch_uuid')->nullable();

            $table->timestamps();

            $table->index('log_name', 'idx_activity_log_name');
            $table->index('event', 'idx_activity_event');
        });
    }
};

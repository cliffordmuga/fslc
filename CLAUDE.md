# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

Laravel 12 marketing/portfolio CMS for **Forefront Solutions (K) Ltd** (forefrontsolutions.co.ke) — HMIS/digital-health consultancy positioning, an insights hub, lead capture, and an admin content manager. Blade + Alpine.js + Tailwind on the front; TinyMCE in admin. Target host is **cPanel shared hosting** (Apache, PHP 8.2/8.3), so cache, session, and queue all use the `database` driver by default.

## Commands

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed          # sqlite or mysql; seeders create an admin user + demo content

# Development
composer dev                        # concurrently: php artisan serve + queue:listen + pail + vite
php artisan serve                   # app only
npm run dev                         # vite dev server
npm run build                       # production assets -> public/build

# Tests (PHPUnit, not Pest — despite the pest allow-plugin in composer.json)
composer test                       # config:clear then artisan test
php artisan test
php artisan test --filter=SitemapGenerationTest          # single test class
php artisan test tests/Feature/CmsLeadTest.php           # single file
npm run test:e2e                    # Playwright (playwright.config.js)

# Lint / format
./vendor/bin/pint                   # Laravel Pint, default ruleset (no pint.json)

# Custom artisan
php artisan sitemap:generate [--ping]
php artisan cache:status
php artisan cache:bust-content                 # bumps the content cache buster
php artisan csp:status [--days=7]              # CSP violation report summary
php artisan images:backfill-blur               # generate LQIP blur placeholders (needs GD)
php artisan leads:migrate-attachments          # move legacy lead files into storage/app/private
php artisan lead-events:prune [--days=N]        # delete lead_events older than the retention window
php artisan forefront:generate-brand-assets
```

`APP_URL` must match the vhost exactly (e.g. `http://fslc.test` or `http://localhost/fslc/public`). `AppServiceProvider` calls `URL::forceRootUrl(config('app.url'))`, so a mismatch breaks every generated link/redirect. A 419 on forms is almost always an `APP_URL`/`SESSION_DOMAIN` mismatch.

## Deployment

`DEPLOYMENT.md` is authoritative. Key points that affect how code must behave:

- The Laravel root is uploaded **above** `public_html/`; the contents of `public/` go **into** `public_html/`. `public/.htaccess` carries a server-specific `RewriteBase /` and `SetEnv APP_LARAVEL_PATH ...` that must **not** be committed.
- `composer deploy` runs `config:cache route:cache view:cache event:cache` + `sitemap:generate`. Anything read from `env()` outside a `config/` file will be null once config is cached — always go through `config()`.
- No SSH assumed. Caches are cleared in production via `GET /clear-cache?token=<CLEAR_CACHE_TOKEN>`. That route is defined in `bootstrap/app.php` (not `routes/web.php`) specifically so it works with **no session/cache DB tables present**; keep it dependency-free.
- Cron runs `php artisan schedule:run` every minute → `queue:work --stop-when-empty --max-time=45` (processes queued mail), daily `sitemap:generate`, weekly `lead-events:prune` (`routes/console.php`).

## Architecture

### Bootstrap & middleware (`bootstrap/app.php`)

Laravel 12 slim skeleton — there is **no `app/Http/Kernel.php`**. All global middleware, aliases, CSRF exceptions, and trusted proxies are configured in `bootstrap/app.php`. Order matters and is deliberate:

1. **Prepended:** `ForceHttps` → `HandleRedirects` (redirects resolve before anything else)
2. route handling
3. **Appended:** `SecurityHeaders` (CSP etc.), then `PublicPageCache`, then `TrackPageViews` (records in `terminate()`, so it counts cache HITs and never blocks the response)

Aliases: `2fa` (`Verify2FA`), `admin.2fa` (`RequireAdminTwoFactor`), `role` (`CheckRole`), `page.cache`. CSRF is disabled for `csp-report`, `api/cta/click`, `lead-events`.

### Content model — one polymorphic table

`Content` (`contents` table) holds every content kind, discriminated by a `type` string column. Status is `draft` / `published` gated by `published_at <= now()` (`scopePublished`). `booted()` auto-generates a unique slug and an excerpt on create/update. `Content::getUrlAttribute` maps `type` → route name via `config('routes.content_types')` and caches the result.

The **`App\Enums\ContentType` enum is the single source of truth** for content types: `portfolio`, `services`, `page`, `about`, `mission`, `vision`, `intro`, `blog`, plus the *fragment* types `timeline_item` / `faq_item` (embedded on hub pages, no public detail URL). It provides:

- `labels()` — admin-dropdown labels; `Content::typeLabels()` delegates here (there is no longer a `Content::TYPES` constant).
- `isFragment()` — true for `timeline_item` / `faq_item`.
- `detailPath($slug)` / `redirectPathFor($type, $slug)` — public path for a type, `null` for fragments; used by `ContentObserver` to build 301s on slug change.

When adding a type: add the enum case + a `labels()` entry, then wire `config/routes.php` and `routes/web.php` if it has a public URL.

Tags (`content_tag` pivot) are edited from the content create/edit forms via the `admin.content.partials.tags-field` partial — checkboxes for existing `Tag`s plus a comma-separated `new_tags` field that `ContentController::syncTags()` creates on the fly (deduped by slug). There is no standalone admin Tag CRUD; hub copy (`Tag::hub_intro` / `meta_description`) is set by the seeder or tinker.

### Caching (multi-layer — the core complexity of this codebase)

| Layer | Where | Invalidation |
|---|---|---|
| Full-page HTML | `PublicPageCache` middleware | TTL (`public_page_cache.ttl_seconds`, default 120s) + global buster. Key = `md5(buster \| locale \| device \| path \| allow-listed sorted query)`. Skips authenticated users, admin/auth/form/api paths. `?nocache=1` bypasses. Adds `X-Page-Cache: HIT/MISS/SKIP/BYPASS`. |
| Query/fragment cache | `ContentService` via `ContentCacheManager` | Tag-based (only on redis/memcached — degrades to plain keys on the database driver) + `cacheVersion()` + content buster. |
| Model cache keys/tags | `Cacheable` trait | `saved`/`deleted` model events call `getCacheKeys()` / `getCacheTags()`. Pattern clearing is redis-only and off by default. |
| SEO fields | `Seoable` trait | Per-model `seo:<class>:<id>:*` keys, busted on `saved`/`deleted`. |
| Admin sidebar / dashboard | `App\Support\AdminUiCache` | Explicit `forget*` calls from observers. |
| Global buster | `App\Services\CacheBuster` | `bump()` invalidates full-page **and** content-fragment caches at once. `cache:bust-content` and `ContentObserver` call into this. |

`ContentObserver::saved()` busts content cache, refreshes admin option caches, and dispatches `RegenerateSitemapJob` when the item is published. `ContentObserver::updating()` auto-creates a 301 `Redirect` row when a slug changes (path from `ContentType::redirectPathFor()`; fragment types are skipped).

When adding a cached read, register its key/tag so an observer or trait clears it — stale content after an admin edit is the usual bug.

### Device-aware image variants

`ImageService` writes resized variants (`main`, `small`, `thumbnail`, `mobile`, `mobile_retina`) and LQIP blur placeholders to the `public_uploads` disk (`config/image.php`, `config/uploads.php`), dropping a hardening `.htaccess` in each upload dir. `Content` exposes `featured_image`, `gallery`, `gallery_for_view` accessors that group variant rows by a stripped filename key. `ContentService` picks `mobile`/`thumbnail` vs `main` per request using Jenssegers Agent — which is **why `PublicPageCache` includes a device bucket in its cache key**. Images relate via the `Imageable` trait (`morphMany`).

### SEO

`SeoMetadata` attaches to any model via `Seoable` (`morphOne`). `SeoService` builds meta/OG/JSON-LD; `SeoScoreService` + `App\View\Components\Admin\SeoScore` show an editor score.

`PageSeoService` owns per-page-type structured data, canonical URLs, and copy (heavily Kenya/HMIS-specific — treat the hardcoded strings there as content, not config). `ContentService` keeps thin delegating wrappers — `getSeoData()`, `getPageSchemas()`, `getTagSeoData()`, `applyPaginatedHubSeo()`, `applyFilteredHubSeo()` — so controllers call `ContentService` as before.

Sitemap: Spatie sitemap + `SitemapService`, served through `SitemapController` (`xml` / `html` / admin `generate`). `GET /sitemap.xml` self-heals if missing/outdated; `RegenerateSitemapJob` fires on content changes; daily schedule. The generated `public/sitemap.xml` is gitignored.

**Static CMS pages** (`/privacy`, `/terms`) fetch their optional CMS body via `ContentService::getPage($slug)` — a cached `type=page` lookup, not a raw query per request. `FrontendController::previewContent` is the one deliberately-uncached content read (draft preview via a signed URL).

### Leads & funnel

`ContactController::store` is the **single** submission handler — `/contact` and the site-wide `/leads` alias both point at it (honeypot, `LeadSpamService`, secure file upload, conversion tracking). Contact + leads share one `lead-submissions` rate limiter (defined in `AppServiceProvider`, `app.lead_submissions_per_minute`) so hitting both endpoints can't double the allowance. `LeadEvent` + `LeadEventController` (`POST /lead-events`) capture client-side funnel events; `lead-events:prune` (scheduled weekly, `config('forefront.lead_events.retention_days', 90)`) keeps that table bounded. Lead file attachments live in `storage/app/private/leads/` (never web-accessible; download via a signed admin route).

**Admin notification** goes through `Lead::notifyAdmin()` — queues the `ContactReceived` mail once, guarded by `leads.admin_notified_at`. Called from `ContactController::store` (non-spam path) and from `LeadController::markAsNotSpam` / bulk `mark_not_spam`, so a real lead auto-flagged as spam still reaches the inbox when an admin clears the flag.

**Spam scoring**: `LeadSpamService::score()` returns a 0–100 score; `isSpam()` compares it to `config('forefront.lead_spam.threshold', 45)` (config, not raw `env()`, so it survives `config:cache`). A spam lead is still stored (`is_spam = true`) but never emailed.

**Lead attribution**: `source_content_id` (the page the form was on) and `service_content_id` (the service picked in the form) — relations `sourceContent()` / `serviceContent()`. `scopeHighIntent()` and the `is_high_intent` accessor treat a lead as high-intent when its `inquiry_type` isn't general/newsletter, it isn't spam, **and** it carries either content id.

### Auth & authorization

Breeze + optional Socialite (gated by `config('features.social_login_enabled')` / `SOCIAL_LOGIN_ENABLED` — routes 404 when off). Two roles on `users.role`: `admin`, `user` (`User::ROLE_*`, `isAdmin()`, `AssignsDefaultRole` trait). **All `/admin/*` routes require `auth` + `verified` + `2fa` + `admin.2fa` + `role:admin`**, and `/profile` is held to the same 2FA bar. 2FA is `pragmarx/google2fa` via `TwoFactorService`.

### Redirects

`Redirect` model + `HandleRedirects` middleware (runs first, GET/HEAD only, skips `/`, `/admin`, `/api`, degrades safely if the table isn't migrated, caches lookups 1h, tracks hits). Admin CRUD plus import/test tooling. `/blog/*` → `/insights/*` legacy redirects are hardcoded in `routes/web.php`.

### Other conventions

- **`app/helpers.php` is autoloaded** (composer `files`) and holds ~40 view helpers: `setting()`/`settings()` (24h-cached DB settings), `meta_title()`, `canonical_url()`, `render_cms_content()`, `sanitize_rich_html()`, `image_srcset()`, `hero_asset()`, `generate_excerpt()`, etc. Check here before writing a new Blade helper.
- **Settings** are DB-backed key/values (`Setting` model, `SettingService`, admin UI) — company name, address, phone, founded year, etc. flow through `setting()`.
- **Activity log**: Spatie `LogsActivity` on `Content`, `User`, and others; `ActivityLogController` + cached recent-activity in the admin sidebar.
- **Config lives in custom files**: `config/forefront.php` (portfolio pillars, per-service lead config, tag hub intros, inquiry UX copy, `lead_spam.threshold`, `lead_events.retention_days`), `config/public_page_cache.php`, `config/seo.php`, `config/image.php`, `config/uploads.php`, `config/security.php` (CSP), `config/features.php`.
- **CSP**: `SecurityHeaders` middleware emits the policy; browsers post violations to `POST /csp-report` → `CspReportController`; review with `php artisan csp:status`.
- Tests use sqlite `:memory:`, `array` cache, `sync` queue, `array` mail (`phpunit.xml`). The `*RefinementTest` / `*RemediationTest` feature tests are regression guards for specific past fixes — run the relevant one after touching frontend pages, SEO, or uploads. `tests/Unit` covers the pure logic (`ContentType`, `ContentCacheManager`, `PageSeoService`, `LeadSpamService`, image cache keys).
- **Watch the `date` cast on `PageAnalytic`**: it persists as `Y-m-d 00:00:00`, so `where('date', $ymd)` only matches on MySQL (real `DATE` column). Use `PageAnalytic::forDay($contentId, $ymd)` (whereDate lookup + create + race retry) then `increment()` on the returned row — both `TrackPageViews` and `ContactController::trackConversion()` go through it.

# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

Laravel 12 marketing/portfolio CMS for **Forefront Solutions (K) Ltd** (forefrontsolutions.co.ke) — HMIS/digital-health consultancy positioning, an insights hub, lead capture, and an admin content manager. Blade + Alpine.js + Tailwind on the front; TinyMCE in admin. Target host is a **self-managed VPS** (Nginx, PHP-FPM 8.3). `config/cache.php`/`.env.example` default new installs to `redis` for cache/session/queue (falling back to `file`/`database` automatically if the redis extension isn't available), but the **live production `.env` is still on `database`** as of this writing — see "Live status" under Deployment for the actual current state and why.

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

- Standard Laravel layout on the VPS — `public/` is the Nginx docroot directly (`/var/www/forefront/public`), no split-directory indirection. `public/index.php` is stock Laravel; don't reintroduce path-resolution hacks.
- `composer deploy` runs `config:cache route:cache view:cache event:cache` + `sitemap:generate`. Anything read from `env()` outside a `config/` file will be null once config is cached — always go through `config()`.
- Caches can also be cleared without SSH via `GET /clear-cache?token=<CLEAR_CACHE_TOKEN>`. That route is defined in `bootstrap/app.php` (not `routes/web.php`) specifically so it works with **no session/cache DB tables present**; keep it dependency-free.
- Cron runs `php artisan schedule:run` every minute for scheduled *jobs* (daily `sitemap:generate`, weekly `lead-events:prune`, hourly `cache:prune-stale`) — see `routes/console.php`. Queue *processing* is a separate, persistent worker, not cron-triggered — in production that's **Supervisor**, program group `forefront-worker` (restart with `sudo supervisorctl restart forefront-worker:*` — the `:*` matters, see "Live status" below); `deploy/systemd/forefront-queue.service` is a reference unit that was never actually installed. Restart after any deploy that changes queued job code (`php artisan queue:restart` also works, without a full process restart).
- **Deploy**: `scripts/sync-production.sh` commits + pushes to a `production` git remote (bare repo on the VPS); a server-side `post-receive` hook does a delta `git checkout -f`, `composer install --no-dev`, conditional asset rebuild, `migrate --force`, cache rebuild, and a queue-worker restart, wrapped in maintenance mode. `deploy/nginx/forefront.conf` and `deploy/systemd/forefront-queue.service` are reference configs for the server side, not auto-applied by the deploy script.

### Live status (as of 2026-09-22)

Deployed and live at `https://forefrontsolutions.co.ke` (+`www`), on the same
Hetzner VPS as `khmis.fsl.co.ke` (178.105.20.188) and `erp.fsl.co.ke`. Migrated
from a previous cPanel host — the cPanel DB was dumped and imported into
`forefront_db` before the first VPS deploy, so real production data (leads,
content, users) carried over. The old cPanel hosting is still around as a
rollback safety net; decommission it once the VPS instance has been confirmed
stable for a while.

Three things differ from what's described above/in `DEPLOYMENT.md` —
worth a deliberate decision, not just aligning the docs to match:

- **Cache/session/queue are on `database`, not `redis`**, in the actual
  production `.env` — matches `.env.example`'s safer default ("Redis optional
  at scale — do not switch unless infra supports it"), not the "Redis by
  default" claim in the paragraph above. Infra *does* support it now (a
  shared, unauthenticated Redis instance is already running on the VPS for
  khmis) — switching is straightforward whenever it's actually wanted.
- **Queue worker runs under Supervisor**, not the systemd unit
  (`deploy/systemd/forefront-queue.service` was never installed). Restart
  with `sudo supervisorctl restart forefront-worker:*` — note the `:*`
  suffix is required; a bare `restart forefront-worker` (or `start`) fails
  with "no such process" when Supervisor has the group in a `FATAL` state
  (e.g. right after a fresh provision, before the app's `artisan` file
  exists for the first time).
- **`www` is not redirected to the apex domain** — both serve identical
  content directly, unlike `deploy/nginx/forefront.conf`'s reference config
  which 301s `www` → apex.

Also still open: `CLEAR_CACHE_TOKEN` is unset in production `.env`, so the
`/clear-cache?token=…` route described above won't work until it's set.

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
| Query/fragment cache | `ContentService` via `ContentCacheManager` | Tag-based on `redis`/`memcached`, degrades to plain keys on `database`/`file` (currently active in production — see "Live status" under Deployment) + `cacheVersion()` + content buster. |
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

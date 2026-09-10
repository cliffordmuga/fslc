# Deployment Guide — forefrontsolutions.co.ke (cPanel Shared Hosting)

This is the authoritative step-by-step deployment guide for this Laravel 12 project
on cPanel shared hosting (Apache, PHP 8.3).

---

## Target server file layout

```
/home/pwdfvylw/
├── sitefolder/
│   └── fslc/               ← entire Laravel project (ABOVE web root — secure)
│       ├── app/
│       ├── bootstrap/
│       ├── config/
│       ├── database/
│       ├── resources/
│       ├── routes/
│       ├── storage/           ← writable by web user
│       ├── vendor/
│       ├── artisan
│       ├── .env               ← production credentials (copied from .env.production)
│       └── ...
└── public_html/           ← web root (domain points here)
    ├── .htaccess          ← from public/.htaccess  (with SetEnv line uncommented)
    ├── index.php          ← from public/index.php
    ├── build/             ← from public/build/
    ├── images/            ← from public/images/
    ├── plugins/           ← from public/plugins/
    ├── storage/           ← symlink or folder (see Step 7)
    ├── uploads/           ← from public/uploads/
    ├── favicon.ico
    ├── robots.txt
    └── sitemap.xml
```

**Why this layout?** Keeping `app/`, `config/`, `vendor/`, `.env`, etc. outside
`public_html/` means they are **never directly accessible** from the internet.

---

## Pre-flight checklist (do this ONCE, locally)

```bash
# Build frontend assets
npm run build

# Verify no obvious errors
php artisan route:list --compact
```

---

## Step 1 — Prepare the .env file

1. Copy `.env.production` → `.env.production.local` (working copy — never commit)
2. Fill in every `<<< CHANGE ME` value:
   - **DB credentials** from cPanel → MySQL Databases
   - **MAIL_PASSWORD** (your Zoho SMTP password)
   - **CLEAR_CACHE_TOKEN** — generate a random secret:
     ```
     openssl rand -hex 32
     ```
3. Double-check these are correct:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://forefrontsolutions.co.ke
   SESSION_SECURE_COOKIE=true
   QUEUE_CONNECTION=database
   CACHE_STORE=database

   # Logging — daily rotation + warning level so the log file can't fill your quota
   LOG_CHANNEL=stack
   LOG_STACK=daily
   LOG_DAILY_DAYS=14
   LOG_LEVEL=warning

   # Trusted proxies — leave blank; it defaults to "*", which is correct on
   # cPanel/LiteSpeed (PHP is only reachable via the host front end). Set a
   # comma-separated IP/CIDR list only to narrow it.
   TRUSTED_PROXIES=

   # Image optimisation — set to false unless cPanel has the CLI binaries
   # (jpegoptim, pngquant, optipng, svgo). With it true on a host that lacks
   # them (or disables proc_open), every upload shells out ~6 times for nothing.
   IMAGE_OPTIMIZE=false
   ```
   Optional tuning knobs (sane defaults, override only if needed):
   ```
   LEAD_SPAM_THRESHOLD=45          # score ≥ this ⇒ stored as spam, not emailed
   LEAD_EVENTS_RETENTION_DAYS=90   # lead_events older than this are pruned weekly
   ```

---

## Step 2 — Edit public/.htaccess before uploading

Open `public/.htaccess` locally and:

**2a. Change RewriteBase to the domain root:**
```apache
RewriteBase /
```
The default `/fslc/public` is for local Laragon subdirectory dev only. Leaving
it as-is on production makes every non-homepage URL (e.g. `/about`) 404 at the
Apache level — the front controller rewrite resolves to a filesystem path
that doesn't exist, even though `/` itself still loads via `DirectoryIndex`.

**2b. Uncomment and set the APP_LARAVEL_PATH line:**
```apache
SetEnv APP_LARAVEL_PATH /home/pwdfvylw/sitefolder/fslc
```

**2c. (Optional) Uncomment the HTTPS redirect:**
```apache
RewriteCond %{HTTPS} off
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```
Not required for correctness — `App\Http\Middleware\ForceHttps` already redirects
http → https automatically whenever `APP_ENV=production`. Uncommenting this is
only a minor optimisation (redirects before PHP boots).

> Do not commit the RewriteBase/APP_LARAVEL_PATH changes. They're server-specific.
> After deploying, revert them locally so local dev keeps working.

---

## Step 3 — Upload the project (via cPanel File Manager or FTP)

### 3a. Build a production-only vendor/ before uploading

Dev-only packages (`laravel/breeze`, `laravel/sail`, `laravel/pail`, `mockery`,
`phpunit`, `fakerphp/faker`) must never reach the server. Right before
uploading:

```bash
composer install --no-dev --optimize-autoloader
```

Upload `vendor/` in this state. Afterwards, restore your local dev environment:

```bash
composer install
```

### 3b. Upload Laravel root → /home/pwdfvylw/sitefolder/fslc/

Upload everything EXCEPT:
- `node_modules/`      (never needed on server)
- `.git/`              (security risk)
- `public/`            (goes to public_html, not here)
- `storage/app/`       (if it has large local files)

Upload includes:
```
app/         bootstrap/   config/      database/    resources/
routes/      storage/     tests/       vendor/      artisan
composer.json composer.lock .env (your filled-in production file)
```

### 3c. Upload public/ contents → /home/pwdfvylw/public_html/

Upload the CONTENTS of `public/` (not the folder itself):
```
.htaccess    index.php    build/       images/
plugins/     uploads/     favicon.ico  robots.txt   sitemap.xml
```

> If `public_html/` already has a default cPanel `index.html`, delete it first.

---

## Step 4 — Set directory permissions (cPanel File Manager)

| Path                               | Permission |
|------------------------------------|-----------|
| `/home/pwdfvylw/sitefolder/fslc/storage`      | 775        |
| `/home/pwdfvylw/sitefolder/fslc/bootstrap/cache` | 775     |
| `/home/pwdfvylw/public_html/uploads` | 775      |

Select each folder in File Manager → right-click → Change Permissions.

---

## Step 5 — Create the MySQL database (cPanel)

1. cPanel → **MySQL Databases**
2. Create database: `pwdfvylw_portfolio`
3. Create user: `pwdfvylw_portfolio` (or any name) with a strong password
4. **Add User to Database** → select All Privileges
5. Update your `.env` on the server with these values

---

## Step 6 — Run database migrations (cPanel Terminal or SSH)

```bash
cd /home/pwdfvylw/sitefolder/fslc
php artisan migrate --force
```

If you don't have SSH, use **cPanel → Terminal**.

After migrations succeed, run the deploy optimisations:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan sitemap:generate
```

Or run all at once with the composer script:
```bash
composer run-script deploy
```

---

## Step 7 — Storage symlink

The `storage/` folder in `public_html/` must point to `/home/pwdfvylw/sitefolder/fslc/storage/app/public`.

**Via SSH/Terminal:**
```bash
cd /home/pwdfvylw/public_html
ln -s /home/pwdfvylw/sitefolder/fslc/storage/app/public storage
```

**Without SSH (cPanel File Manager workaround):**
- Upload `storage/app/public/` contents directly into `public_html/storage/`
- This is not a symlink but works for initial deployment
- New uploads via the app will land in the right place automatically

---

## Step 8 — Set up the Cron job (cPanel)

1. cPanel → **Cron Jobs**
2. Set to **Every Minute** (`* * * * *`)
3. Command:
   ```
   cd /home/pwdfvylw/sitefolder/fslc && php artisan schedule:run >> /dev/null 2>&1
   ```

**One** crontab entry is all you need — `schedule:run` dispatches everything in
`routes/console.php`:

| Task | Frequency | Why it matters |
|------|-----------|----------------|
| `queue:work --stop-when-empty` | every minute | processes queued mail (bulk un-spam notifications) and `RegenerateSitemapJob` |
| `sitemap:generate` | daily 03:00 | rebuilds `public/sitemap.xml` |
| `cache:prune-stale` | hourly | deletes expired rows from the `cache` table (the database cache store never evicts stale page-cache keys on its own) |
| `lead-events:prune` | weekly Sun 02:30 | trims the `lead_events` funnel table |

> If the cron stops, the site still works — lead **submission** emails are sent
> inline, not queued. Only bulk admin actions and sitemap regeneration wait on
> the worker.

---

## Step 9 — Smoke test

Visit these URLs and verify they work:

| URL | Expected |
|-----|----------|
| `https://forefrontsolutions.co.ke` | Homepage loads, no errors |
| `https://forefrontsolutions.co.ke/login` | Login page loads |
| `https://forefrontsolutions.co.ke/admin` | Redirects to login |
| `https://forefrontsolutions.co.ke/sitemap.xml` | XML sitemap |
| `https://forefrontsolutions.co.ke/robots.txt` | Robots file |
| `http://forefrontsolutions.co.ke` | Redirects to https:// |
| `https://www.forefrontsolutions.co.ke` | Redirects to non-www |

---

## Updating the site (re-deployments)

For most changes:
```bash
# 1. Upload changed files
# 2. Clear caches via the protected route:
https://forefrontsolutions.co.ke/clear-cache?token=YOUR_CLEAR_CACHE_TOKEN
```

For database schema changes:
```bash
cd /home/pwdfvylw/sitefolder/fslc
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

> After uploading new files, always `composer dump-autoload -o` if the upload
> added a class (new command, service, etc.) and you use the optimised
> autoloader.

---

## Troubleshooting

| Symptom | Fix |
|---------|-----|
| **500 error on every page** | Check `storage/` + `bootstrap/cache/` are writable (755/775); confirm `.env` exists; confirm `APP_KEY` is set |
| **DB connection error** | Wrong DB host/user/password in `.env`; use `localhost` not `127.0.0.1` on cPanel |
| **CSS/JS 404** | `public/build/manifest.json` missing — run `npm run build` locally and re-upload `public/build/` |
| **Images not loading** | Run `php artisan storage:link` or manually create the `public_html/storage/` folder |
| **Lead attachments** | Stored in `storage/app/private/leads/` (not web-accessible). Download via Admin → Leads → attachment link. Migrate legacy files: `php artisan leads:migrate-attachments` |
| **Blur placeholders (LQIP)** | New uploads get blur automatically. Backfill existing CMS images: `php artisan images:backfill-blur` (requires GD) |
| **419 Page Expired** | `APP_URL` doesn't match the actual domain; `SESSION_DOMAIN` misconfigured |
| **Redirect loop / "too many redirects" on HTTPS** | Proxy not trusted — Laravel thinks the request is plain HTTP. `TRUSTED_PROXIES` defaults to `*` which fixes this; if you set it explicitly, make sure it's `*` or includes the host's proxy. Then `php artisan config:cache`. |
| **Emails not sending** | Check Zoho SMTP credentials; `MAIL_EHLO_DOMAIN` must be your domain, not `localhost`. Lead-submission emails send inline (not queued), so a failure here shows in `storage/logs`. |
| **`cache` table growing large** | Confirm the hourly `cache:prune-stale` is running (via `schedule:run`). Manual: `php artisan cache:prune-stale`. |
| **Maintenance mode stuck** | Delete `/home/pwdfvylw/sitefolder/fslc/storage/framework/maintenance.php` |

---

## Cache management (without SSH)

The app has a protected cache-clear route:

```
GET https://forefrontsolutions.co.ke/clear-cache?token=YOUR_CLEAR_CACHE_TOKEN
```

Set `CLEAR_CACHE_TOKEN` in `.env`. Keep the token secret — anyone with it
can clear your caches.

---

## PHP requirements

Verify in cPanel → MultiPHP Manager:

- PHP **8.2+** (currently using 8.3)
- Extensions required: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`,
  `json`, `bcmath`, `ctype`, `xml`, `gd` (for image resizing), `fileinfo`
- Recommended INI settings (cPanel → MultiPHP INI Editor):
  ```
  upload_max_filesize = 10M
  post_max_size       = 10M
  memory_limit        = 256M
  max_execution_time  = 90
  ```
  (The cron-driven queue worker runs `--max-time=45`; a 90s cap keeps a
  comfortable margin instead of racing a 60s limit.)

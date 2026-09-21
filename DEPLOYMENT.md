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

`public/.htaccess` deliberately carries **no `RewriteBase`** — Apache derives
the base from the physical directory holding the file, so the committed line
works unmodified whether the docroot is a subdirectory (local Laragon:
`/fslc/public`) or the domain root (production: `public_html/`). Nothing to
edit here for routing. (An earlier version hardcoded `RewriteBase /fslc/public`
with instructions to change it before every upload and revert it after — that
edit-then-revert-every-deploy pattern is exactly the kind of step that gets
skipped or done backwards, and is the likely cause of at least one past
broken-link/asset-URL production incident.)

**Uncomment and set the APP_LARAVEL_PATH line** (this one genuinely is
server-specific — there's no way to infer a cPanel username):
```apache
SetEnv APP_LARAVEL_PATH /home/pwdfvylw/sitefolder/fslc
```

**(Optional) Uncomment the HTTPS redirect:**
```apache
RewriteCond %{HTTPS} off
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```
Not required for correctness — `App\Http\Middleware\ForceHttps` already redirects
http → https automatically whenever `APP_ENV=production`. Uncommenting this is
only a minor optimisation (redirects before PHP boots).

> Do not commit the APP_LARAVEL_PATH change — it's server-specific. Revert it
> locally after deploying so local dev keeps working.

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

**Prefer the git-based deploy below** — it replaces manual file uploads and
removes the "which files did I change" guesswork. The steps here are the
fallback for hosts without cPanel's Git Version Control feature, or for a
one-off manual patch.

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

## Git-based deploys (recommended)

`.cpanel.yml` at the repo root drives cPanel's **Git Version Control**
feature: it copies the built-in `public/` assets into `public_html/`, runs
`composer install --no-dev`, migrations, and the same cache-rebuild steps as
the manual process — as one clicked action instead of a file-by-file upload.
It never touches `.env`, `storage/`, `vendor/`, `public_html/uploads/`, or the
`public_html/storage` symlink; only files tracked in git move.

**Why this over a raw folder sync (rsync/WinSCP mirror, FTP mirror tools):** a
two-way sync between your local project and the server would overwrite the
server's `.env` (DB credentials, `APP_URL`, `APP_DEBUG`) with your local dev
values, and could delete server-only files a mirror doesn't know about
(uploaded lead attachments, CMS images, the storage symlink). Deploying from
git is one-way and only moves what's actually tracked.

### One-time setup

1. cPanel → **Git Version Control** → **Create**.
   - Clone URL: `https://github.com/cliffordmuga/fslc.git`
   - Repository Path: `/home/pwdfvylw/sitefolder/forefront` — a **fresh,
     empty** directory; `git clone` refuses a non-empty target, so this
     can't be `sitefolder/fslc` if that already holds an old hand-uploaded
     copy. Not `public_html/` either way.
   - Branch: `main`.
2. In that directory (cPanel Terminal, or File Manager if no Terminal):
   copy `.env.example` → `.env`, fill in production values (Step 1 above) —
   **reuse the existing production DB credentials** from the old deployment's
   `.env` rather than creating a new database, so leads/content aren't
   orphaned — then `php artisan key:generate`, `php artisan storage:link`.
3. **Frontend assets**: `public/build/` (compiled CSS/JS) is committed to
   the repo, so a fresh clone already has it — nothing to build or upload by
   hand at this step. (Earlier revisions of this doc had you build and
   manually place `public/build/` in two locations; that's no longer
   necessary — and was actively dangerous, since cPanel's "Deploy HEAD
   Commit" does a clean checkout that silently wiped any manually-placed,
   untracked file in the repo path, which is what caused
   `ViteManifestNotFoundException` to keep coming back. See the note at the
   end of this section for what to do when you change frontend code.)
4. Open `.cpanel.yml` and confirm `DEPLOYPATH` matches your actual
   `public_html` path, and that `composer`/`php` resolve on this account
   (cPanel Terminal: `which composer`, `which php`; if either prints
   nothing, use the full `/opt/cpanel/...` path cPanel's docs give you and
   edit the task lines accordingly).
5. Run migrations once by hand the first time (`php artisan migrate --force`)
   so the deploy user's DB permissions are confirmed working before you rely
   on the automated task.
6. **The cutover**: edit `public_html/.htaccess`'s `SetEnv APP_LARAVEL_PATH`
   to point at the new path, then reload the site to confirm.

### Every deploy after that

```bash
git push origin main            # from your local machine, as usual
```
Then in cPanel → Git Version Control → this repo → **Manage** → **Update from
Remote** (pulls the new commits) → **Deploy HEAD Commit** (runs the
`.cpanel.yml` tasks: copy public assets, `composer install --no-dev`,
`migrate --force`, cache rebuild, `sitemap:generate`). Watch the task output
in the cPanel UI — if `composer install` fails on a memory-constrained plan,
build `vendor/` locally instead and upload it (Step 3a above), then re-run
"Deploy HEAD Commit" to redo just the cache/migration tasks.

**If a commit changes any CSS/JS/Blade component that affects the built
bundle**, run `npm run build` locally and commit the resulting `public/build/`
changes along with your source changes, then `git push` and deploy as usual —
the deploy script doesn't build it for you, but since `public/build/` now
travels with the repo, the normal clone/checkout/rsync flow carries it to
`public_html/build/` automatically, no manual upload needed. Forgetting to
rebuild before committing a frontend change is the only way this breaks —
the manifest will just reference the previous build's output until you do.

---

## Troubleshooting

| Symptom | Fix |
|---------|-----|
| **500 error on every page** | Check `storage/` + `bootstrap/cache/` are writable (755/775); confirm `.env` exists; confirm `APP_KEY` is set |
| **DB connection error** | Wrong DB host/user/password in `.env`; use `localhost` not `127.0.0.1` on cPanel |
| **CSS/JS 404 / ViteManifestNotFoundException** | `public/build/manifest.json` missing or stale — run `npm run build` locally, commit `public/build/`, push, and deploy (git-based deploys only; see "Git-based deploys" above) |
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

# Deployment Guide — forefrontsolutions.co.ke (VPS)

Authoritative deployment guide for this Laravel 12 project on a self-managed
VPS (Nginx, PHP-FPM 8.3, Redis, MySQL). Shared-hosting/cPanel deployment is
retired — see git history (`.cpanel.yml`, the old `public/index.php`
`APP_LARAVEL_PATH` indirection) if you ever need to reconstruct that path.

---

## Architecture

```
Nginx (TLS termination, static files) → PHP-FPM 8.3 → Laravel app
                                                      ↘ Redis (cache/session/queue)
                                                      ↘ MySQL (app data)
systemd: forefront-queue.service → persistent `queue:work redis`
cron: `* * * * * php artisan schedule:run` → scheduled jobs only (not queue processing)
```

- **Server layout**: standard Laravel — `/var/www/forefront` is the app root, `/var/www/forefront/public` is the Nginx docroot directly. No split `public_html/` directory, no `APP_LARAVEL_PATH` env var, no custom `index.php`.
- **Reference configs** (not auto-applied — compare against whatever's actually live and reconcile): `deploy/nginx/forefront.conf`, `deploy/systemd/forefront-queue.service`.

---

## Deploying

```bash
bash scripts/sync-production.sh
# or: bash scripts/sync-production.sh --message="Homepage refresh"
# or: bash scripts/sync-production.sh --dry-run    (preview only, no push)
```

This commits any local changes and pushes to the `production` git remote — a
bare repo on the VPS (`deploy@178.105.20.188:~/forefront.git`). A server-side
`post-receive` hook then does a delta deploy: `git checkout -f` (updates only
changed files, handles deletions), `composer install`, conditional asset
rebuild, `migrate --force`, cache rebuild, and a queue-worker restart —
wrapped in maintenance mode. That hook lives on the server, not in this repo,
so its exact current behavior isn't something this doc can guarantee; verify
with `ssh deploy@178.105.20.188 'cat ~/forefront.git/hooks/post-receive'` if
you need to check or change it.

Requires: SSH key at `~/.ssh/khmis_deploy`, git remote `production` (add with
`git remote add production deploy@178.105.20.188:~/forefront.git` if missing).

**Verify after deploying**:
```bash
ssh -i ~/.ssh/khmis_deploy deploy@178.105.20.188 'cat /var/www/forefront/storage/app/deployment.json'
```

---

## Environment (.env)

Key values that differ from local dev — see `.env.example` for the full list:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://forefrontsolutions.co.ke
SESSION_SECURE_COOKIE=true

SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=<set one — don't leave Redis unauthenticated even on localhost-only binding>
# NOTE: production's actual .env is still on `database` for all three as of
# this writing — infra supports redis (a shared instance already runs on
# this VPS for khmis) but nobody's flipped the switch yet. See "Live status"
# in CLAUDE.md before assuming either state.

# Logging — daily rotation + warning level
LOG_CHANNEL=stack
LOG_STACK=daily
LOG_DAILY_DAYS=14
LOG_LEVEL=warning

# Trusted proxies — see the comment in bootstrap/app.php before changing this.
# '*' is safe only if PHP-FPM is unreachable from outside the box (verify with
# `ss -tlnp | grep php-fpm`). Narrow to Nginx's IP/CIDR otherwise.
TRUSTED_PROXIES=

# Image optimisation — safe to enable on a VPS you control, once the CLI
# binaries are installed (jpegoptim, pngquant, optipng, svgo).
IMAGE_OPTIMIZE=false
```

Optional tuning knobs (sane defaults, override only if needed):
```
LEAD_SPAM_THRESHOLD=45          # score ≥ this ⇒ stored as spam, not emailed
LEAD_EVENTS_RETENTION_DAYS=90   # lead_events older than this are pruned weekly
```

---

## Queue worker (Supervisor)

Queue processing runs as a persistent worker, not the cron-triggered burst
job used on shared hosting. In production this runs under **Supervisor**,
program group `forefront-worker`:

```bash
sudo supervisorctl restart forefront-worker:*
```

The `:*` suffix is required — it targets the whole numprocs group. A bare
`restart forefront-worker` (or `start`) fails with "no such process" if
Supervisor has the group in a `FATAL` state (e.g. right after a fresh
provision, before `artisan` existed yet). The `post-receive` hook is expected
to restart this automatically after every deploy; verify it does if queued
jobs stop processing.

`deploy/systemd/forefront-queue.service` is a **reference** unit for hosts
that use systemd instead of Supervisor — it was never actually installed on
the current VPS, so don't assume `systemctl restart forefront-queue` does
anything there.

---

## Cron

One crontab entry — `schedule:run` dispatches everything in `routes/console.php`:
```
* * * * * cd /var/www/forefront && php artisan schedule:run >> /dev/null 2>&1
```

| Task | Frequency | Why it matters |
|------|-----------|----------------|
| `sitemap:generate` | daily 03:00 | rebuilds `public/sitemap.xml` |
| `cache:prune-stale` | hourly | no-ops now that `CACHE_STORE=redis` (only prunes a database-backed store); harmless to leave scheduled |
| `lead-events:prune` | weekly Sun 02:30 | trims the `lead_events` funnel table |

Queue processing is **not** on this schedule — see the systemd worker above.

---

## Cache management (without SSH)

```
GET https://forefrontsolutions.co.ke/clear-cache?token=YOUR_CLEAR_CACHE_TOKEN
```
Defined in `bootstrap/app.php` (not `routes/web.php`) so it works even with
no cache/session tables migrated. Keep `CLEAR_CACHE_TOKEN` secret.

---

## Smoke test

| URL | Expected |
|-----|----------|
| `https://forefrontsolutions.co.ke` | Homepage loads, no errors |
| `https://forefrontsolutions.co.ke/login` | Login page loads |
| `https://forefrontsolutions.co.ke/admin` | Redirects to login |
| `https://forefrontsolutions.co.ke/sitemap.xml` | XML sitemap, correct domain in every `<loc>` |
| `https://forefrontsolutions.co.ke/robots.txt` | Robots file |
| `http://forefrontsolutions.co.ke` | Redirects to https:// |
| `https://www.forefrontsolutions.co.ke` | Redirects to non-www |

`scripts/http-smoke.php`, `scripts/detail-smoke.php`, and `scripts/link-crawl.php` automate a broader pass — run them against the live URL after a deploy that touches routing, SEO, or content-detail pages.

---

## Troubleshooting

| Symptom | Fix |
|---------|-----|
| **500 error on every page** | Check `storage/` + `bootstrap/cache/` are writable by the PHP-FPM user; confirm `.env` exists; confirm `APP_KEY` is set |
| **DB connection error** | Wrong DB host/user/password in `.env` |
| **CSS/JS 404 / ViteManifestNotFoundException** | `public/build/manifest.json` missing or stale — run `npm run build` locally, commit `public/build/` (it's tracked in git, not gitignored), then deploy |
| **Images not loading** | Run `php artisan storage:link` |
| **Lead attachments** | Stored in `storage/app/private/leads/` (not web-accessible). Download via Admin → Leads → attachment link. Migrate legacy files: `php artisan leads:migrate-attachments` |
| **Blur placeholders (LQIP)** | New uploads get blur automatically. Backfill existing CMS images: `php artisan images:backfill-blur` (requires GD) |
| **419 Page Expired** | `APP_URL` doesn't match the actual domain; `SESSION_DOMAIN` misconfigured |
| **Redirect loop / "too many redirects" on HTTPS** | `TRUSTED_PROXIES` misconfigured — see the comment in `bootstrap/app.php`. Then `php artisan config:cache`. |
| **Emails not sending** | Check SMTP credentials; `MAIL_EHLO_DOMAIN` must be your domain, not `localhost`. Lead-submission emails send inline (not queued), so a failure here shows in `storage/logs` |
| **Queued jobs not processing** | Check the Supervisor worker: `sudo supervisorctl status forefront-worker:*`. Restart if crashed/FATAL: `sudo supervisorctl restart forefront-worker:*` (the `:*` is required) |
| **Sitemap not reflecting a content change** | `SitemapController::xml()` self-heals based on a 24h cache timestamp, not content freshness — hit `/clear-cache` to force a check, or wait for the daily `sitemap:generate` cron. If it stays stale even after that, check `storage/logs/laravel-<date>.log` for `Failed to generate sitemap` — usually the file-permission issue below, failing silently |
| **`Failed to generate sitemap` / any `Permission denied` writing to `storage`/`bootstrap/cache`/`public`** | See "File permissions & the `deploy`/`www-data` split" below |
| **Maintenance mode stuck** | Delete `/var/www/forefront/storage/framework/maintenance.php` |

---

## PHP requirements

- PHP **8.3**
- Extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `json`, `bcmath`, `ctype`, `xml`, `gd` (image resizing), `fileinfo`, `redis`
- Recommended `php.ini` settings:
  ```
  upload_max_filesize = 10M
  post_max_size       = 10M
  memory_limit        = 256M
  max_execution_time  = 90
  ```

---

## File permissions & the `deploy`/`www-data` split

`git checkout -f` (the `deploy` user) and PHP-FPM (`www-data`) both write into
`storage/`, `bootstrap/cache/`, and `public/` — deploys create/update files as
`deploy`, requests create/update files (sitemap.xml, uploads, logs) as
`www-data`. Without deliberate setup, whichever user *didn't* create a file
can't overwrite it later, causing `Permission denied` failures that are easy
to mistake for something else (a stale sitemap that silently won't
regenerate is the recurring symptom — `SitemapService::generate()` catches
the exception and just logs it, so nothing crashes, it just quietly stops
updating).

**One-time setup** (already done on the current VPS — reapply if provisioning
a new box, or if a fresh `git checkout` ever recreates these dirs from
scratch):
```bash
sudo usermod -aG www-data deploy
sudo chgrp -R www-data /var/www/forefront/storage /var/www/forefront/bootstrap/cache /var/www/forefront/public
sudo chmod -R g+w /var/www/forefront/storage /var/www/forefront/bootstrap/cache /var/www/forefront/public
sudo find /var/www/forefront/storage /var/www/forefront/bootstrap/cache /var/www/forefront/public -type d -exec chmod g+s {} \;
```
`chgrp`/`chmod` alone only fix files that already exist — every *new* file
PHP-FPM creates afterward still gets whatever its process umask dictates
(typically `644`, no group-write), silently reintroducing the same failure.
`php_admin_value[umask]` in the FPM pool config does **not** work — `umask`
isn't a real PHP ini directive, so FPM accepts the line without error but it
has no effect. The actual fix is a systemd service override (applies to
**all** PHP-FPM pools on the box — `erp`/`khmis` and `www` too, not just
`forefront`, since they share one `php8.3-fpm.service`):
```bash
sudo mkdir -p /etc/systemd/system/php8.3-fpm.service.d
printf "[Service]\nUMask=0002\n" | sudo tee /etc/systemd/system/php8.3-fpm.service.d/override.conf
sudo systemctl daemon-reload
sudo systemctl restart php8.3-fpm
```
Verify: `systemctl show php8.3-fpm -p UMask` should print `UMask=0002`; a
freshly-created file under `public/`/`storage/` should be `664`, not `644`.

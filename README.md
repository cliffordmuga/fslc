# Forefront Solutions (K) Ltd — CMS

Laravel 12 portfolio and consultancy CMS for [Forefront Solutions](https://forefrontsolutions.co.ke): HMIS-led positioning, insights hub, lead capture, and admin content management.

## Stack

- PHP 8.2+, Laravel 12, Blade + Alpine.js, Tailwind CSS
- Database-backed cache, session, and queue (shared hosting / cPanel friendly)
- Vite for frontend assets

## Local setup (Laragon)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

Set `APP_URL` to match your local vhost (e.g. `http://fslc.test`).

## Tests

```bash
composer test
# or
php artisan test
```

## Production deploy

See **[DEPLOYMENT.md](DEPLOYMENT.md)** for cPanel upload layout, `.env` values, cron (`schedule:run`), and smoke tests.

**Local pre-deploy** (build assets + cache config/routes/views):

```powershell
# Windows (PowerShell)
.\deploy\deploy.ps1
```

```bash
# Linux / Mac / Laragon Git Bash
bash deploy/deploy.sh
```

Quick production checklist:

- `APP_ENV=production`, `APP_DEBUG=false`
- `QUEUE_CONNECTION=database` + cron every minute
- `SESSION_SECURE_COOKIE=true`, `TRUSTED_PROXIES=*` (behind cPanel/Cloudflare)
- `npm run build` and upload `public/build/`
- `php artisan migrate --force` then `composer deploy`

## Key routes

| Path | Purpose |
|------|---------|
| `/` | Homepage |
| `/insights` | Blog / insights hub |
| `/portfolio`, `/services` | Case studies & service pillars |
| `/contact` | Lead capture |
| `/health` | JSON health check |
| `/admin` | CMS (2FA required) |

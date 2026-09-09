# Local pre-deploy: build assets + Laravel production caches.
# Usage: .\deploy\deploy.ps1

$ErrorActionPreference = "Stop"

$Root = Split-Path -Parent $PSScriptRoot
Set-Location $Root

Write-Host "==> Building frontend assets (Vite)..." -ForegroundColor Cyan
npm run build
if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }

Write-Host "==> Running Laravel deploy optimisations..." -ForegroundColor Cyan
composer deploy --no-interaction
if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }

# Production caches break subdirectory local URLs (http://localhost/fslc/public).
$envLine = Get-Content .env -ErrorAction SilentlyContinue | Where-Object { $_ -match '^\s*APP_ENV\s*=' } | Select-Object -First 1
if ($envLine -match 'local') {
    Write-Host "==> Clearing config/route caches for local subdirectory dev..." -ForegroundColor Yellow
    php artisan config:clear --ansi
    php artisan route:clear --ansi
}

Write-Host "==> Deploy prep complete." -ForegroundColor Green
Write-Host "    Upload public/build/ and run migrations on the server if needed."

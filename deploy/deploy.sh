#!/usr/bin/env bash
# Local pre-deploy: build assets + Laravel production caches.
# Usage (Linux/Mac/cPanel): bash deploy/deploy.sh
# Laragon on Windows: C:\laragon\bin\git\bin\bash.exe deploy/deploy.sh

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

echo "==> Building frontend assets (Vite)..."
npm run build

echo "==> Running Laravel deploy optimisations..."
composer deploy --no-interaction

if grep -qE '^\s*APP_ENV\s*=\s*local' .env 2>/dev/null; then
  echo "==> Clearing config/route caches for local subdirectory dev..."
  php artisan config:clear --ansi
  php artisan route:clear --ansi
fi

echo "==> Deploy prep complete."
echo "    Upload public/build/ and run migrations on the server if needed."

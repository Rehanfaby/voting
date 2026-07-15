#!/usr/bin/env bash
#
# post-deploy.sh — Run ON THE SERVER (in the app root) after every deploy.
#
# It puts the app into a correct production state and smoke-tests it, so a bad
# deploy is caught immediately instead of showing users a blank/500 page.
#
# What it does:
#   1. Validates .env (a malformed .env is the #1 cause of the white screen).
#   2. Reconciles vendor/ for production with --no-dev (prevents the stale
#      "Facade\Ignition\IgnitionServiceProvider not found" class of errors).
#   3. Clears stale bootstrap/framework caches.
#   4. Smoke-tests the live URL and fails loudly if it isn't a real 200 page.
#
# USAGE (from the app root, e.g. ~/domains/mulemagc.com/public_html):
#   ./scripts/post-deploy.sh
#   ./scripts/post-deploy.sh https://mulemagc.com/   # custom URL to smoke-test
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

SITE_URL="${1:-https://mulemagc.com/}"

echo "==> [1/4] Validating .env"
./scripts/check-env.sh

echo "==> [2/4] Installing production dependencies (--no-dev)"
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> [3/4] Clearing stale caches"
rm -f bootstrap/cache/config.php \
      bootstrap/cache/packages.php \
      bootstrap/cache/services.php \
      bootstrap/cache/routes*.php
php artisan config:clear
php artisan cache:clear
php artisan view:clear
# NOTE: do NOT run `php artisan route:cache` here — routes/api.php uses a
# Closure route, so route caching will fail on this app.

echo "==> [4/4] Smoke-testing $SITE_URL"
code="$(curl -sS -o /dev/null -w '%{http_code}' "$SITE_URL" || echo 000)"
size="$(curl -sS -o /dev/null -w '%{size_download}' "$SITE_URL" || echo 0)"
echo "    HTTP $code, ${size} bytes"

if [ "$code" != "200" ] || [ "${size:-0}" -lt 1000 ]; then
  echo "" >&2
  echo "!! Smoke test FAILED (expected HTTP 200 with a real page)." >&2
  echo "   Check the log:  tail -n 80 storage/logs/laravel.log" >&2
  exit 1
fi

echo "==> Deploy OK — site is up."

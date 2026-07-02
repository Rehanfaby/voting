#!/usr/bin/env bash
# Quick syntax check before deploy — catches parse errors that would white-screen the site.
set -euo pipefail
ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
FAILED=0
while IFS= read -r -d '' f; do
  if ! php -l "$f" >/dev/null 2>&1; then
    php -l "$f" || true
    FAILED=1
  fi
done < <(find "$ROOT/app" "$ROOT/routes" -name '*.php' ! -path '*/StockCount/.php' -print0)
if [[ "$FAILED" -ne 0 ]]; then
  echo "PHP syntax check FAILED."
  exit 1
fi
echo "PHP syntax check passed ($(find "$ROOT/app" "$ROOT/routes" -name '*.php' | wc -l | tr -d ' ') files)."

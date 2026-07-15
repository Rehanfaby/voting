#!/usr/bin/env bash
#
# bump-version.sh — Increment APP_VERSION (patch by default).
#
# Updates config/app.php defaults used for cache-busting and UI labels.
#
# USAGE
#   ./scripts/bump-version.sh           # 2.3.3 -> 2.3.4
#   ./scripts/bump-version.sh minor     # 2.3.3 -> 2.4.0
#   ./scripts/bump-version.sh major     # 2.3.3 -> 3.0.0
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

LEVEL="${1:-patch}"
APP_FILE="config/app.php"

current="$(php -r "
\$c = file_get_contents('$APP_FILE');
if (!preg_match(\"/env\\('APP_VERSION',\\s*'([^']+)'\\)/\", \$c, \$m)) {
    fwrite(STDERR, \"Could not find APP_VERSION default in $APP_FILE\\n\");
    exit(1);
}
echo \$m[1];
")"

IFS='.' read -r major minor patch <<< "$current"
major="${major:-0}"
minor="${minor:-0}"
patch="${patch:-0}"

case "$LEVEL" in
  major) major=$((major + 1)); minor=0; patch=0 ;;
  minor) minor=$((minor + 1)); patch=0 ;;
  patch) patch=$((patch + 1)) ;;
  *) echo "Unknown level: $LEVEL (use patch|minor|major)" >&2; exit 1 ;;
esac

next="${major}.${minor}.${patch}"

php -r "
\$file = '$APP_FILE';
\$c = file_get_contents(\$file);
\$c = preg_replace(
    \"/env\\('APP_VERSION',\\s*'[^']+'\\)/\",
    \"env('APP_VERSION', '$next')\",
    \$c,
    -1,
    \$count
);
if (\$count < 1) {
    fwrite(STDERR, \"Failed to rewrite APP_VERSION in \$file\\n\");
    exit(1);
}
file_put_contents(\$file, \$c);
"

echo "$next"

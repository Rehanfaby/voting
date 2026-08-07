#!/usr/bin/env bash
#
# bump-version.sh — Increment APP_VERSION (patch by default).
#
# Updates config/app.php (single source of truth for UI label + cache-busting).
# Version is NOT read from .env.
#
# Rollover rules (patch level):
#   After x.y.10  →  x.(y+1).0
#   After x.10.10 →  (x+1).0.0
# Example: 3.0.10 → 3.1.0 ; 3.10.10 → 4.0.0
#
# USAGE
#   ./scripts/bump-version.sh           # patch (with rollover)
#   ./scripts/bump-version.sh minor     # 3.1.4 -> 3.2.0 (3.10.x -> 4.0.0)
#   ./scripts/bump-version.sh major     # 3.2.4 -> 4.0.0
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

LEVEL="${1:-patch}"
APP_FILE="config/app.php"
MAX_SEGMENT=10

current="$(php -r "
\$c = file_get_contents('$APP_FILE');
if (!preg_match(\"/'version'\\s*=>\\s*'([^']+)'/\", \$c, \$m)) {
    fwrite(STDERR, \"Could not find 'version' in $APP_FILE\\n\");
    exit(1);
}
echo \$m[1];
")"

IFS='.' read -r major minor patch <<< "$current"
major="${major:-0}"
minor="${minor:-0}"
patch="${patch:-0}"

# Collapse illegal high segments (e.g. 3.0.30 → 3.1.0) before applying a bump.
normalized=0
while [ "$patch" -gt "$MAX_SEGMENT" ]; do
  patch=0
  minor=$((minor + 1))
  normalized=1
done
while [ "$minor" -gt "$MAX_SEGMENT" ]; do
  minor=0
  major=$((major + 1))
  normalized=1
done

# If we only needed to repair an over-cap version, that repair is the next release.
if [ "$normalized" -eq 1 ] && [ "$LEVEL" = "patch" ]; then
  :
else
  case "$LEVEL" in
    major)
      major=$((major + 1))
      minor=0
      patch=0
      ;;
    minor)
      minor=$((minor + 1))
      patch=0
      if [ "$minor" -gt "$MAX_SEGMENT" ]; then
        major=$((major + 1))
        minor=0
      fi
      ;;
    patch)
      if [ "$patch" -ge "$MAX_SEGMENT" ]; then
        patch=0
        minor=$((minor + 1))
        if [ "$minor" -gt "$MAX_SEGMENT" ]; then
          major=$((major + 1))
          minor=0
        fi
      else
        patch=$((patch + 1))
      fi
      ;;
    *)
      echo "Unknown level: $LEVEL (use patch|minor|major)" >&2
      exit 1
      ;;
  esac
fi

next="${major}.${minor}.${patch}"

php -r "
\$file = '$APP_FILE';
\$c = file_get_contents(\$file);
\$c = preg_replace(
    \"/'version'\\s*=>\\s*'[^']+'/\",
    \"'version' => '$next'\",
    \$c,
    1,
    \$count
);
if (\$count < 1) {
    fwrite(STDERR, \"Failed to rewrite 'version' in \$file\\n\");
    exit(1);
}
\$c = preg_replace(
    \"/'version_label'\\s*=>\\s*'MGT V\\.[^']*'/\",
    \"'version_label' => 'MGT V.$next'\",
    \$c,
    1,
    \$count2
);
if (\$count2 < 1) {
    fwrite(STDERR, \"Failed to rewrite 'version_label' in \$file\\n\");
    exit(1);
}
file_put_contents(\$file, \$c);
"

echo "$next"

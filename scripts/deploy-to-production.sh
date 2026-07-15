#!/usr/bin/env bash
#
# deploy-to-production.sh — Bump version, push to GitHub, deploy on Hostinger.
#
# Every deploy increments APP_VERSION (patch) so CSS/JS cache-bust and the UI
# version label move forward automatically.
#
# USAGE
#   ./scripts/deploy-to-production.sh                 # bump + commit + push + deploy
#   ./scripts/deploy-to-production.sh --skip-push     # deploy only (no bump/push)
#   ./scripts/deploy-to-production.sh --no-bump       # push + deploy without version bump
#   ./scripts/deploy-to-production.sh minor           # bump minor version then deploy
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

SSH_HOST="${SSH_HOST:-hostinger}"
REMOTE_DIR="${REMOTE_DIR:-domains/mulemagc.com/public_html}"
SKIP_PUSH=0
NO_BUMP=0
BUMP_LEVEL="patch"

for arg in "$@"; do
  case "$arg" in
    --skip-push) SKIP_PUSH=1 ;;
    --no-bump) NO_BUMP=1 ;;
    patch|minor|major) BUMP_LEVEL="$arg" ;;
    -h|--help)
      grep '^#' "$0" | sed 's/^# \{0,1\}//'
      exit 0
      ;;
    *) echo "Unknown option: $arg" >&2; exit 1 ;;
  esac
done

chmod +x scripts/bump-version.sh scripts/git-deploy.sh scripts/post-deploy.sh scripts/check-env.sh

if [ "$SKIP_PUSH" -eq 0 ] && [ "$NO_BUMP" -eq 0 ]; then
  echo "==> Bumping APP_VERSION ($BUMP_LEVEL)"
  NEW_VERSION="$(./scripts/bump-version.sh "$BUMP_LEVEL")"
  echo "    New version: $NEW_VERSION"

  if ! git diff --quiet -- config/app.php; then
    git add config/app.php
    git commit -m "$(cat <<EOF
Bump app version to ${NEW_VERSION} for production deploy.

EOF
)"
  else
    echo "    config/app.php unchanged (already at $NEW_VERSION)"
  fi
fi

if [ "$SKIP_PUSH" -eq 0 ]; then
  echo "==> Ensuring origin/main is up to date"
  git push origin main
fi

# Resolve the version that will land on production (from local tree).
DEPLOY_VERSION="$(php -r "
\$c = file_get_contents('config/app.php');
preg_match(\"/env\\('APP_VERSION',\\s*'([^']+)'\\)/\", \$c, \$m);
echo \$m[1] ?? '';
")"

echo "==> Deploying on $SSH_HOST:$REMOTE_DIR (v${DEPLOY_VERSION})"
ssh "$SSH_HOST" "cd $REMOTE_DIR && chmod +x scripts/git-deploy.sh scripts/post-deploy.sh scripts/check-env.sh scripts/bump-version.sh 2>/dev/null; ./scripts/git-deploy.sh"

# Keep production .env APP_VERSION in sync for cache-busting via env()
if [ -n "$DEPLOY_VERSION" ]; then
  echo "==> Syncing APP_VERSION=${DEPLOY_VERSION} on production .env"
  ssh "$SSH_HOST" "cd $REMOTE_DIR && \
    if grep -q '^APP_VERSION=' .env; then \
      sed -i 's|^APP_VERSION=.*|APP_VERSION=${DEPLOY_VERSION}|' .env; \
    else \
      printf '\nAPP_VERSION=${DEPLOY_VERSION}\n' >> .env; \
    fi && \
    php artisan config:clear && php artisan view:clear && \
    echo \"    production APP_VERSION=\$(grep '^APP_VERSION=' .env | cut -d= -f2)\""
fi

echo "==> Deploy complete — Mulema v${DEPLOY_VERSION}"

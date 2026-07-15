#!/usr/bin/env bash
#
# git-deploy.sh — Production deploy via git (run ON THE SERVER).
#
# Pulls new commits from origin/main with fast-forward only (never rewrites
# history or force-pushes). Then runs post-deploy.sh for caches and smoke test.
#
# Production .env is protected with skip-worktree so git never overwrites it.
# Server-only differences (uploaded images, bootstrap/cache) stay as-is unless
# a future commit explicitly changes those tracked files.
#
# USAGE (from app root):
#   ./scripts/git-deploy.sh
#   ./scripts/git-deploy.sh https://mulemagc.com/
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

SITE_URL="${1:-https://mulemagc.com/}"

git config core.fileMode false
git config pull.ff only

# Protect production secrets from ever being checked out by git.
if [ -f .env ]; then
  git update-index --skip-worktree .env 2>/dev/null || true
fi

echo "==> Fetching origin/main"
git fetch origin main

BEFORE="$(git rev-parse HEAD)"
git merge --ff-only origin/main
AFTER="$(git rev-parse HEAD)"

if [ "$BEFORE" = "$AFTER" ]; then
  echo "==> Already at latest commit ($AFTER)"
else
  echo "==> Updated $BEFORE -> $AFTER"
fi

./scripts/post-deploy.sh "$SITE_URL"

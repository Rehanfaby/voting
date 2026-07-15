#!/usr/bin/env bash
#
# deploy-to-production.sh — Push to GitHub and deploy via git on Hostinger.
#
# Run from your local machine. Does NOT rsync code; production pulls from git.
#
# USAGE
#   ./scripts/deploy-to-production.sh              # push main (if needed) + deploy
#   ./scripts/deploy-to-production.sh --skip-push  # deploy only (already pushed)
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

SSH_HOST="${SSH_HOST:-hostinger}"
REMOTE_DIR="${REMOTE_DIR:-domains/mulemagc.com/public_html}"
SKIP_PUSH=0

for arg in "$@"; do
  case "$arg" in
    --skip-push) SKIP_PUSH=1 ;;
    -h|--help)
      grep '^#' "$0" | sed 's/^# \{0,1\}//'
      exit 0
      ;;
    *) echo "Unknown option: $arg" >&2; exit 1 ;;
  esac
done

if [ "$SKIP_PUSH" -eq 0 ]; then
  echo "==> Ensuring origin/main is up to date"
  git push origin main
fi

echo "==> Deploying on $SSH_HOST:$REMOTE_DIR"
ssh "$SSH_HOST" "cd $REMOTE_DIR && chmod +x scripts/git-deploy.sh scripts/post-deploy.sh scripts/check-env.sh && ./scripts/git-deploy.sh"

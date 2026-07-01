#!/usr/bin/env bash
#
# sync-media.sh — Pull uploaded media (and optionally the DB) from the
# production server (mulemagc.com) down to this local checkout over SSH.
#
# The app code and database are already local; only user-uploaded media
# lives on the server. This script rsyncs those folders into ./public.
#
# USAGE
#   ./scripts/sync-media.sh              # sync media folders
#   ./scripts/sync-media.sh --dry-run    # preview what would transfer
#   ./scripts/sync-media.sh --db         # also dump + download the live DB
#
# CONFIG
#   Edit the variables below, or override them inline, e.g.:
#   SSH_PORT=65002 SSH_USER=u152889834 ./scripts/sync-media.sh
#
set -euo pipefail

# ---- Hostinger SSH details (find these in hPanel -> Advanced -> SSH Access) ----
SSH_USER="${SSH_USER:-u152889834}"           # SSH username from hPanel
SSH_HOST="${SSH_HOST:-mulemagc.com}"          # host or server IP from hPanel
SSH_PORT="${SSH_PORT:-65002}"                 # Hostinger SSH port is usually 65002

# Absolute path to the app's PUBLIC dir on the server (must end without slash).
# On Hostinger this is typically the public_html that serves the site.
REMOTE_PUBLIC="${REMOTE_PUBLIC:-domains/mulemagc.com/public_html/public}"

# ---- Local paths (do not normally need to change) ------------------------------
PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
LOCAL_PUBLIC="$PROJECT_ROOT/public"

# Folders that hold uploaded media (relative to the public dir).
MEDIA_DIRS=(
  "images/employee"
  "images/category"
  "images/product"
  "logo"
  "img"
)

# ---- Args ----------------------------------------------------------------------
DRY_RUN=""
DO_DB=""
for arg in "$@"; do
  case "$arg" in
    --dry-run) DRY_RUN="--dry-run" ;;
    --db)      DO_DB="1" ;;
    -h|--help) grep '^#' "$0" | sed 's/^# \{0,1\}//'; exit 0 ;;
    *) echo "Unknown option: $arg" >&2; exit 1 ;;
  esac
done

SSH_CMD="ssh -p ${SSH_PORT}"
REMOTE="${SSH_USER}@${SSH_HOST}"

echo "==> Syncing media from ${REMOTE}:${REMOTE_PUBLIC}"
echo "    into ${LOCAL_PUBLIC}"
[ -n "$DRY_RUN" ] && echo "    (dry run — nothing will be written)"
echo

for dir in "${MEDIA_DIRS[@]}"; do
  echo "--> ${dir}"
  mkdir -p "${LOCAL_PUBLIC}/${dir}"
  rsync -avz --human-readable $DRY_RUN \
    -e "${SSH_CMD}" \
    "${REMOTE}:${REMOTE_PUBLIC}/${dir}/" \
    "${LOCAL_PUBLIC}/${dir}/"
  echo
done

# ---- Optional: pull a fresh copy of the live database --------------------------
if [ -n "$DO_DB" ]; then
  : "${DB_NAME:=u152889834_voting_ticket}"
  : "${DB_USER:=u152889834_voting_ticket}"
  : "${DB_PASS:=U152889834_voting_ticket}"
  DUMP="$PROJECT_ROOT/storage/app/live-${DB_NAME}-$(date +%Y%m%d-%H%M%S).sql"

  echo "==> Dumping live database '${DB_NAME}' on server..."
  # shellcheck disable=SC2029
  ${SSH_CMD} "${REMOTE}" "mysqldump -u '${DB_USER}' -p'${DB_PASS}' '${DB_NAME}'" > "$DUMP"
  echo "    Saved to: $DUMP"
  echo
  echo "    To import locally:"
  echo "      mysql -u ${DB_USER} -p --port=3308 ${DB_NAME} < \"$DUMP\""
fi

echo "==> Done. Hard-refresh the site; images now load from local files."

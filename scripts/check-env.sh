#!/usr/bin/env bash
#
# check-env.sh — Validate a .env file BEFORE it can white-screen the site.
#
# Laravel refuses to boot if .env has a single malformed line, which shows up
# as a blank page in production. This catches the classic mistakes:
#   - using ':' instead of '=' (e.g. STRIPE_KEY:pk_live_...)
#   - spaces around '=' , or a name with invalid characters
#   - a value containing spaces/#/quotes that isn't wrapped in "..."
#
# USAGE
#   ./scripts/check-env.sh            # checks ./.env
#   ./scripts/check-env.sh /path/.env # checks a specific file
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
ENV_FILE="${1:-$ROOT/.env}"

if [ ! -f "$ENV_FILE" ]; then
  echo "!! .env not found at: $ENV_FILE" >&2
  exit 1
fi

fail=0
lineno=0
while IFS= read -r line || [ -n "$line" ]; do
  lineno=$((lineno + 1))

  # Skip blank lines and comments.
  [ -z "${line//[[:space:]]/}" ] && continue
  case "$line" in
    \#*|[[:space:]]*\#*) continue ;;
  esac

  # Every real line must start with NAME= (letters/digits/underscore, then '=').
  if ! printf '%s' "$line" | grep -Eq '^[[:space:]]*[A-Za-z_][A-Za-z0-9_]*='; then
    echo "!! Invalid line $lineno: ${line:0:70}" >&2
    fail=1
    continue
  fi

  # Value must be quoted if it contains a space or '#' (otherwise dotenv breaks).
  value="${line#*=}"
  case "$value" in
    \"*\"|\'*\'|"") : ;;  # quoted or empty is fine
    *[[:space:]]*|*'#'*)
      echo "!! Line $lineno: value has a space or '#' but isn't quoted: ${line:0:70}" >&2
      fail=1
      ;;
  esac
done < "$ENV_FILE"

if [ "$fail" -ne 0 ]; then
  echo "" >&2
  echo "check-env: FAILED — every line must be NAME=value (no ':' , no unquoted spaces)." >&2
  exit 1
fi

echo "check-env: OK — $ENV_FILE looks valid."

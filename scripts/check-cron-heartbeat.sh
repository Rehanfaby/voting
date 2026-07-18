#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.." || cd ~/domains/mulemagc.com/public_html

ROOT="${1:-.}"
cd "$ROOT"

FILE="app/Http/Controllers/HomeController.php"
if ! grep -q 'cron-reconcile.heartbeat' "$FILE"; then
  python3 - <<'PY' || php -r '
$path = "app/Http/Controllers/HomeController.php";
$c = file_get_contents($path);
if (strpos($c, "cron-reconcile.heartbeat") !== false) { echo "already\n"; exit(0); }
$needle = "        \$result = \$this->reconcilePendingVotes(\$days, \$phone);\n\n        return response()->json([\"ok\" => true] + \$result);";
$insert = "        \$result = \$this->reconcilePendingVotes(\$days, \$phone);\n\n        @file_put_contents(storage_path(\"app/cron-reconcile.heartbeat\"), json_encode([\n            \"at\" => date(\"Y-m-d H:i:s\"),\n            \"result\" => \$result,\n        ]) . PHP_EOL, FILE_APPEND);\n\n        return response()->json([\"ok\" => true] + \$result);";
if (strpos($c, $needle) === false) { fwrite(STDERR, "needle missing\n"); exit(1); }
file_put_contents($path, str_replace($needle, $insert, $c));
echo "patched\n";
'
fi

php artisan config:clear >/dev/null 2>&1 || true
SECRET=$(grep -E '^CRON_SECRET=' .env | cut -d= -f2- | tr -d '"'"'"'')
HB=storage/app/cron-reconcile.heartbeat
rm -f "$HB"

echo "Hitting endpoint once..."
curl -fsS "https://mulemagc.com/cron/reconcile-votes?token=${SECRET}&days=14" >/tmp/cron_hit.json
cat /tmp/cron_hit.json; echo
test -f "$HB" || { echo "ERROR: heartbeat not written after manual hit"; exit 1; }
BASE=$(wc -l < "$HB" | tr -d ' ')
echo "BASELINE_LINES=$BASE"
echo "Waiting 70s for unattended cron..."
sleep 70
AFTER=$(wc -l < "$HB" | tr -d ' ')
echo "AFTER_LINES=$AFTER"
tail -5 "$HB"
if [ "$AFTER" -gt "$BASE" ]; then
  echo "RESULT=CRON_RUNNING"
else
  echo "RESULT=CRON_NOT_DETECTED"
fi

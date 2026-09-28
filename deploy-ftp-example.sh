#!/bin/bash

# ---------------------------------------------------------------------------
# FTP DEPLOYMENT SCRIPT — EXAMPLE / TEMPLATE
#
# For servers reachable only via FTP (no SSH, no rsync, no Composer on the
# server). Uses lftp to mirror the project up to the server.
#
# 1. Copy this file to deploy-ftp.sh:
#       cp deploy-ftp-example.sh deploy-ftp.sh
#
# 2. Fill in your server details below.
#
# 3. Make it executable:
#       chmod +x deploy-ftp.sh
#
# 4. Run it from the project root:
#       ./deploy-ftp.sh            # actually upload
#       ./deploy-ftp.sh --dry-run  # preview what would be uploaded
#
# deploy-ftp.sh is gitignored — your credentials will never be committed.
#
# Requires lftp locally (sudo apt install lftp / brew install lftp).
#
# NOTE: unlike deploy.sh, vendor/ and kirby/ ARE uploaded, because Composer
# can't run on the server. Dependencies are installed locally with
# `composer install --no-dev` first, so they must match the server's PHP
# version (see "php" in composer.json).
#
# Only files newer locally than on the server are uploaded (--only-newer),
# so content edited in the live Panel is not overwritten by older local
# copies. Nothing is ever deleted on the server.
# ---------------------------------------------------------------------------

FTP_USER="your-user"                    # FTP username
FTP_PASS=""                             # FTP password — leave empty to be prompted
FTP_HOST="ftp.your-server.com"          # FTP hostname or IP
FTP_PORT=21                             # FTP port (usually 21)
FTP_TLS=true                            # true = require FTPS (explicit TLS), false = plain FTP
FTP_VERIFY_CERT=true                    # set false if the host's TLS certificate doesn't match FTP_HOST
REMOTE_PATH="/public_html"              # site root as seen from the FTP login (often not the absolute server path)
# ---------------------------------------------------------------------------

set -e

DRY_RUN=""
if [ "$1" == "--dry-run" ]; then
  DRY_RUN="--dry-run"
  echo "→ Dry run — nothing will be uploaded."
fi

if [ -z "${FTP_PASS}" ]; then
  read -r -s -p "FTP password for ${FTP_USER}@${FTP_HOST}: " FTP_PASS
  echo
fi

echo "→ Building assets..."
bun run build

echo "→ Installing production dependencies locally (vendor/, kirby/)..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "→ Deploying to ftp://${FTP_USER}@${FTP_HOST}:${FTP_PORT}${REMOTE_PATH}"
export LFTP_PASSWORD="${FTP_PASS}"
lftp --env-password -u "${FTP_USER}" -p "${FTP_PORT}" "${FTP_HOST}" <<EOF
set ftp:ssl-allow ${FTP_TLS}
set ftp:ssl-force ${FTP_TLS}
set ftp:ssl-protect-data ${FTP_TLS}
set ssl:verify-certificate ${FTP_VERIFY_CERT}
set net:max-retries 2
set net:timeout 20
mirror --reverse --only-newer --no-perms --parallel=4 --verbose ${DRY_RUN} \
  --exclude '^\.git/' \
  --exclude '^\.gitignore$' \
  --exclude '^\.claude/' \
  --exclude '^\.vscode/' \
  --exclude '^\.idea/' \
  --exclude '(^|/)\.DS_Store$' \
  --exclude '^node_modules/' \
  --exclude '^src/' \
  --exclude '^logs/' \
  --exclude '^media/' \
  --exclude '^example/' \
  --exclude '^site/example/' \
  --exclude '^site/accounts/' \
  --exclude '^site/sessions/' \
  --exclude '^site/cache/' \
  --exclude '^[^/]*\.sh$' \
  --exclude '^CLAUDE\.md$' \
  --exclude '^PLAN\.md$' \
  ./ "${REMOTE_PATH}/"
bye
EOF

echo "✓ Deploy complete."

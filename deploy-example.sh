#!/bin/bash

# ---------------------------------------------------------------------------
# DEPLOYMENT SCRIPT — EXAMPLE / TEMPLATE
#
# 1. Copy this file to deploy.sh:
#       cp deploy-example.sh deploy.sh
#
# 2. Fill in your server details below.
#
# 3. Make it executable:
#       chmod +x deploy.sh
#
# 4. Run it from the project root:
#       ./deploy.sh
#
# deploy.sh is gitignored — your credentials will never be committed.
#
# NOTE: vendor/ and kirby/ are excluded from the upload. Composer runs on
# the server after each deploy to install dependencies for the server's
# PHP version. See README for first-time server setup instructions.
# ---------------------------------------------------------------------------

SSH_USER="your-user"                     # SSH username on the server
SSH_HOST="your-server.com"              # server hostname or IP
REMOTE_PATH="/var/www/your-site"        # absolute path to the site root on the server
SSH_PORT=22                             # change if your server uses a non-standard port
PHP_BIN="/usr/bin/php"                  # path to PHP binary on the server (e.g. /usr/local/php83/bin/php on DreamHost)
COMPOSER_BIN="~/composer"              # path to composer on the server
# ---------------------------------------------------------------------------

set -e

echo "→ Building assets..."
bun run build

echo "→ Deploying to ${SSH_USER}@${SSH_HOST}:${REMOTE_PATH}"
rsync -avz --progress \
  --exclude='.git' \
  --exclude='.gitignore' \
  --exclude='node_modules' \
  --exclude='src/' \
  --exclude='logs/' \
  --exclude='vendor/' \
  --exclude='kirby/' \
  --exclude='example/' \
  --exclude='deploy.sh' \
  --exclude='deploy-example.sh' \
  --exclude='CLAUDE.md' \
  --exclude='PLAN.md' \
  --exclude='site/accounts' \
  --exclude='site/sessions' \
  --exclude='site/cache' \
  -e "ssh -p ${SSH_PORT}" \
  ./ "${SSH_USER}@${SSH_HOST}:${REMOTE_PATH}/"

echo "→ Installing dependencies on server..."
ssh -p "${SSH_PORT}" "${SSH_USER}@${SSH_HOST}" \
  "${PHP_BIN} ${COMPOSER_BIN} install --no-dev --optimize-autoloader -d ${REMOTE_PATH}"

echo "→ Setting permissions..."
ssh -p "${SSH_PORT}" "${SSH_USER}@${SSH_HOST}" \
  "chmod -R 775 ${REMOTE_PATH}/content ${REMOTE_PATH}/media ${REMOTE_PATH}/site/cache ${REMOTE_PATH}/site/sessions ${REMOTE_PATH}/site/accounts"

echo "✓ Deploy complete."

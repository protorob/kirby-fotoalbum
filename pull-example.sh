#!/bin/bash

# ---------------------------------------------------------------------------
# PULL SCRIPT — EXAMPLE / TEMPLATE
#
# Reverse of deploy.sh: pulls the content/ folder (flat-file CMS data —
# text files + uploaded images) FROM the server TO local. content/ is
# tracked in git, so after pulling, review with `git diff` / `git status`
# before committing.
#
# 1. Copy this file to pull.sh:
#       cp pull-example.sh pull.sh
#
# 2. Fill in your server details below (same values as deploy.sh).
#
# 3. Make it executable:
#       chmod +x pull.sh
#
# 4. Run it from the project root:
#       ./pull.sh            # actually sync
#       ./pull.sh --dry-run  # preview changes without touching local files
#
# pull.sh is gitignored — your credentials will never be committed.
# ---------------------------------------------------------------------------

SSH_USER="your-user"                     # SSH username on the server
SSH_HOST="your-server.com"              # server hostname or IP
REMOTE_PATH="/var/www/your-site"        # absolute path to the site root on the server
SSH_PORT=22                             # change if your server uses a non-standard port

set -e

DRY_RUN=""
if [ "$1" == "--dry-run" ]; then
  DRY_RUN="--dry-run"
  echo "→ Dry run — no local files will be changed."
fi

echo "→ Pulling content/ from ${SSH_USER}@${SSH_HOST}:${REMOTE_PATH}/content/"
rsync -avz --progress ${DRY_RUN} \
  --exclude='.git' \
  --exclude='.gitignore' \
  -e "ssh -p ${SSH_PORT}" \
  "${SSH_USER}@${SSH_HOST}:${REMOTE_PATH}/content/" ./content/

echo "✓ Pull complete. Run 'git status' / 'git diff' to review changes."

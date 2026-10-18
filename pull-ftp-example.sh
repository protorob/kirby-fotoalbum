#!/bin/bash

# ---------------------------------------------------------------------------
# FTP PULL SCRIPT — EXAMPLE / TEMPLATE
#
# Reverse of deploy-ftp.sh: downloads the content/ folder (flat-file CMS
# data — text files + uploaded images, including gallery selection logs)
# FROM the server TO local. Run it before deploying again so nothing
# edited in the live Panel gets lost. content/ is tracked in git, so after
# pulling, review with `git diff` / `git status` before committing.
#
# 1. Copy this file to pull-ftp.sh:
#       cp pull-ftp-example.sh pull-ftp.sh
#
# 2. Fill in your server details below (same values as deploy-ftp.sh).
#
# 3. Make it executable:
#       chmod +x pull-ftp.sh
#
# 4. Run it from the project root:
#       ./pull-ftp.sh                  # actually download
#       ./pull-ftp.sh --dry-run        # preview what would be downloaded
#       ./pull-ftp.sh --with-accounts  # also download site/accounts/
#                                      # (combinable with --dry-run)
#
# pull-ftp.sh is gitignored — your credentials will never be committed.
#
# Requires lftp locally (sudo apt install lftp / brew install lftp).
#
# Only files newer on the server than locally are downloaded (--only-newer),
# and server timestamps are kept, so a following deploy-ftp.sh won't upload
# them back. Nothing is ever deleted locally.
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
WITH_ACCOUNTS=false
for arg in "$@"; do
  case "$arg" in
    --dry-run)
      DRY_RUN="--dry-run"
      echo "→ Dry run — no local files will be changed."
      ;;
    --with-accounts)
      WITH_ACCOUNTS=true
      echo "→ Including site/accounts/ in the download."
      ;;
    *)
      echo "Unknown option: $arg" >&2
      exit 1
      ;;
  esac
done

if [ -z "${FTP_PASS}" ]; then
  read -r -s -p "FTP password for ${FTP_USER}@${FTP_HOST}: " FTP_PASS
  echo
fi

MIRROR_OPTS="--only-newer --no-perms --parallel=4 --verbose ${DRY_RUN} --exclude '(^|/)\.DS_Store$'"

ACCOUNTS_MIRROR=""
if [ "${WITH_ACCOUNTS}" = true ]; then
  ACCOUNTS_MIRROR="mirror ${MIRROR_OPTS} \"${REMOTE_PATH}/site/accounts/\" ./site/accounts/"
fi

echo "→ Pulling from ftp://${FTP_USER}@${FTP_HOST}:${FTP_PORT}${REMOTE_PATH}"
export LFTP_PASSWORD="${FTP_PASS}"
lftp --env-password -u "${FTP_USER}" -p "${FTP_PORT}" "${FTP_HOST}" <<EOF
set ftp:ssl-allow ${FTP_TLS}
set ftp:ssl-force ${FTP_TLS}
set ftp:ssl-protect-data ${FTP_TLS}
set ssl:verify-certificate ${FTP_VERIFY_CERT}
set net:max-retries 2
set net:timeout 20
mirror ${MIRROR_OPTS} "${REMOTE_PATH}/content/" ./content/
${ACCOUNTS_MIRROR}
bye
EOF

echo "✓ Pull complete. Run 'git status' / 'git diff' to review changes."

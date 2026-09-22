#!/usr/bin/env bash
# ──────────────────────────────────────────────────────────────────────────────
# sync-production.sh — commit + push to production; the server-side
# post-receive hook (~/forefront.git/hooks/post-receive) does the rest as a
# DELTA deploy: `git checkout -f` only touches files that actually changed
# (handles deletions too), then composer install / conditional asset build /
# migrate / cache / queue restart, wrapped in maintenance mode.
#
# Usage (Git Bash on Windows, or Linux/macOS):
#   bash scripts/sync-production.sh
#   bash scripts/sync-production.sh --message="Homepage refresh"
#   bash scripts/sync-production.sh --dry-run
#   bash scripts/sync-production.sh --no-commit
#
# Requires: SSH key at ~/.ssh/khmis_deploy (shared deploy key for this VPS),
# git remote "production" (deploy@178.105.20.188:~/forefront.git)
# ──────────────────────────────────────────────────────────────────────────────

set -euo pipefail

PROJECT_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$PROJECT_ROOT"

SSH_KEY="${KHMIS_DEPLOY_KEY:-$HOME/.ssh/khmis_deploy}"
if [[ -f "$SSH_KEY" ]]; then
  export GIT_SSH_COMMAND="ssh -i ${SSH_KEY} -o IdentitiesOnly=yes -o StrictHostKeyChecking=accept-new"
fi

COMMIT_MSG=""
DRY_RUN=false
NO_COMMIT=false
SKIP_PUSH=false

for arg in "$@"; do
  case "$arg" in
    --dry-run)      DRY_RUN=true ;;
    --no-commit)    NO_COMMIT=true ;;
    --skip-push)    SKIP_PUSH=true ;;
    --message=*)    COMMIT_MSG="${arg#--message=}" ;;
  esac
done

prev=""
for arg in "$@"; do
  if [[ "$prev" == "--message" ]]; then
    COMMIT_MSG="$arg"
  fi
  prev="$arg"
done

echo ""
echo "══════════════════════════════════════════════════════════════"
echo "  Forefront Solutions — Sync to production"
echo "  Target: deploy@178.105.20.188:/var/www/forefront"
if $DRY_RUN; then echo "  MODE: DRY RUN"; fi
echo "══════════════════════════════════════════════════════════════"
echo ""

if ! $NO_COMMIT; then
  if [[ -n "$(git status --porcelain)" ]]; then
    DEFAULT_MSG="Deploy sync $(date -u '+%Y-%m-%d %H:%M UTC')"
    MSG="${COMMIT_MSG:-$DEFAULT_MSG}"
    echo "==> Committing local changes…"
    echo "    Message: $MSG"
    if $DRY_RUN; then
      git status --short
    else
      git add -A
      git commit -m "$MSG"
      echo "    Committed $(git rev-parse --short HEAD)."
    fi
  else
    echo "==> Working tree clean — nothing to commit."
  fi
else
  echo "==> Skipping commit (--no-commit)."
fi

if $SKIP_PUSH; then
  echo "==> Skipping git push (--skip-push)."
elif $DRY_RUN; then
  echo "==> [DRY RUN] Would run: git push production HEAD:main"
  echo "    (server-side post-receive hook then runs the delta deploy)"
else
  if ! git remote get-url production &>/dev/null; then
    echo ""
    echo "==> [error] No git remote named 'production'."
    echo "         Add with: git remote add production deploy@178.105.20.188:~/forefront.git"
    exit 1
  fi
  echo ""
  echo "==> Pushing to git remote production (main)…"
  echo "    (server-side post-receive hook runs the delta deploy — watch its output below)"
  git push production HEAD:main
fi

echo ""
echo "Done. Verify: ssh -i ~/.ssh/khmis_deploy deploy@178.105.20.188 'cat /var/www/forefront/storage/app/deployment.json'"

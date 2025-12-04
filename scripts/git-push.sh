#!/usr/bin/env bash
set -euo pipefail

MSG="${1:-chore: update}"
REMOTE="${2:-origin}"
BRANCH_DEFAULT="$(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo main)"
BRANCH="${3:-$BRANCH_DEFAULT}"

git rev-parse --is-inside-work-tree >/dev/null
git remote get-url "$REMOTE" >/dev/null

CHANGES="$(git status --porcelain)"
if [ -n "$CHANGES" ]; then
  git add -A
  git commit -m "$MSG"
fi

git push "$REMOTE" "$BRANCH"

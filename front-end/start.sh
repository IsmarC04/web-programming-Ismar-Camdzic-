#!/usr/bin/env bash
set -e

PORT="${PORT:-10000}"

# Use public/ if it exists, otherwise serve current directory
if [ -d "./public" ]; then
  DOCROOT="public"
else
  DOCROOT="."
fi

echo "Starting PHP built-in server on 0.0.0.0:${PORT}, serving ${DOCROOT}"
exec php -S "0.0.0.0:${PORT}" -t "${DOCROOT}"

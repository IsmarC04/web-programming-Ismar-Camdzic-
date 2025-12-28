#!/usr/bin/env bash
set -e

PORT="${PORT:-10000}"

# optional wait for database host to be available
if [ -n "${DATABASE_HOST:-}" ]; then
  DB_PORT="${DATABASE_PORT:-5432}"
  echo "Waiting for ${DATABASE_HOST}:${DB_PORT}..."
  until nc -z "${DATABASE_HOST}" "${DB_PORT}"; do
    sleep 1
  done
fi

exec php -S "0.0.0.0:${PORT}" -t public

#!/usr/bin/env bash

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
PROJECTDIR=$( cd "$SCRIPTDIR" && cd ../.. && pwd )

cd "$PROJECTDIR"

ENV_FILE=.env

if [ ! -f ${ENV_FILE} ]; then
  echo "Can't find file: $(git rev-parse --show-toplevel)/.env. Copy .env.sample to .env and follow the instructions inside."
  exit 1
fi

source "$ENV_FILE"
GITHUB_AUTH_TOKEN=$GITHUB_AUTH_TOKEN php -dxdebug.remote_enable=1 -dxdebug.remote_autostart=1 ./vendor/bin/monorepo-builder release "$@"

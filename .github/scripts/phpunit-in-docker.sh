#!/usr/bin/env bash
# Runs the checks for one PHP version inside the official PHP Docker image.
#
# This line supports PHP versions the runner image no longer ships, and workflows using
# a third-party setup action fail to start in this repository.
# Images are pinned by digest so a retagged image cannot change what runs.
#
# Usage: .github/scripts/phpunit-in-docker.sh <php-version>
set -euo pipefail

PHP_VERSION="${1:?php version required}"

case "$PHP_VERSION" in
    5.6) PHP_IMAGE="php@sha256:6ce95208609dc66df163ab936c970b3b34cd901b85c747102c5999f08ade9143"; PLATFORM="5.6.40" ;;
    7.1) PHP_IMAGE="php@sha256:e0e6ced092bbf073e4f3e48545a8e1a2592217b3346ef0cb8b18ecacf8ae1522"; PLATFORM="7.1.33" ;;
    7.4) PHP_IMAGE="php@sha256:620a6b9f4d4feef2210026172570465e9d0c1de79766418d3affd09190a7fda5"; PLATFORM="7.4.33" ;;
    8.1) PHP_IMAGE="php@sha256:76e563191d1ade120313a8736df24154d21da5155c0756f147c0b01bd19d9087"; PLATFORM="8.1.34" ;;
    8.4) PHP_IMAGE="php@sha256:7e7413ed31b8c42b31c939cc83b3758b1cd618f7bf3803f5a0c39f98d4adcbee"; PLATFORM="8.4.27" ;;
    *) echo "unsupported PHP version: $PHP_VERSION" >&2; exit 1 ;;
esac
# composer:2.2, the LTS line that still installs for PHP 5.3 to 7.1 projects.
COMPOSER_IMAGE="composer@sha256:4c156e03ca848b69528dde30996d8922b47a9946e76d5cc563cf7f0898e8aa86"

# Dependencies are resolved for the target PHP version, then run on that version. No
# dependency plugin or script runs while resolving.
REMOVE_PHPSTAN=""
if [ "$PHP_VERSION" = "5.6" ]; then
    REMOVE_PHPSTAN="composer remove --dev --no-update --no-plugins phpstan/phpstan &&"
fi
docker run --rm -u "$(id -u):$(id -g)" -e COMPOSER_HOME=/tmp/composer -v "$PWD":/app -w /app "$COMPOSER_IMAGE" sh -c "
    composer validate &&
    composer config --no-plugins platform.php $PLATFORM &&
    $REMOVE_PHPSTAN
    composer update --no-interaction --no-progress --no-scripts --no-plugins"

# Only the variable names are listed here; docker reads the values from this environment.
ENV_ARGS=()
for name in $(compgen -e | grep '^CHECKOUT_' || true); do
    ENV_ARGS+=(-e "$name")
done

docker run --rm -u "$(id -u):$(id -g)" "${ENV_ARGS[@]}" -v "$PWD":/app -w /app "$PHP_IMAGE" sh -c "
    php -v &&
    if [ -x vendor/bin/phpstan ]; then vendor/bin/phpstan analyse --no-progress --memory-limit=1G; fi &&
    vendor/bin/phpunit --verbose"

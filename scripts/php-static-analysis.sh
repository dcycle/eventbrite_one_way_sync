#!/bin/bash
#
# Static analysis.
#
set -e

echo 'Performing static analsys'
echo 'If you are getting a false negative, use:'
echo ''
echo '// @phpstan-ignore-next-line'
echo ''
echo 'If you are getting unknown class, add a dummy version of the offending'
echo 'class to:'
echo ''
echo './scripts/lib/phpstan/dummy-classes.php'
echo ''

docker run --rm \
  -v "$(pwd)":/var/www/html/modules/custom/eventbrite_one_way_sync \
  dcycle/phpstan-drupal:4 \
  -c /var/www/html/modules/custom/eventbrite_one_way_sync/scripts/lib/phpstan/phpstan.neon \
  /var/www/html/modules/custom \
  --memory-limit=-1

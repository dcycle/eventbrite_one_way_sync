#!/bin/bash
#
# Run tests on the defer module
#
set -e

docker-compose exec -T drupal /bin/bash -c 'drush pmu -y eventbrite_one_way_sync_defer'
docker-compose exec -T drupal /bin/bash -c 'drush en -y dblog'
docker-compose exec -T drupal /bin/bash -c 'drush watchdog-delete all -y'
docker-compose exec -T drupal /bin/bash -c 'drush en -y eventbrite_one_way_sync_defer'
docker-compose exec -T drupal /bin/bash -c "drush ev '\Drupal::service("'"'"eventbrite_one_way_sync_defer.selftest"'"'")->run()'"
# docker-compose exec -T drupal /bin/bash -c 'drush pmu -y eventbrite_one_way_sync_defer'

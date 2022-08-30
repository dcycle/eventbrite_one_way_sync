#!/bin/bash
#
# Run some checks on a running environment
#
set -e

docker-compose exec -T drupal /bin/bash -c 'drush ev "eventbrite_one_way_sync()->endToEndTest()->run();"'

# echo 'Make sure it is possible to uninstall the module'
# docker-compose exec -T drupal /bin/bash -c 'drush pmu -y eventbrite_one_way_sync'

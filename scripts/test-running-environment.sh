#!/bin/bash
#
# Run some checks on a running environment
#
set -e

docker-compose exec -T drupal /bin/bash -c 'drush ev "eventbrite_one_way_sync()->endToEndTest()->run();"'

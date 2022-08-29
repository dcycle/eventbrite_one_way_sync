#!/bin/bash
#
# Run some checks on a running environment
#
set -e

docker-compose exec -T drupal /bin/bash -c "drush ev '\Drupal::service("'"'"eventbrite_one_way_sync.request_response_test"'"'")->run("'"'"eventbrite_one_way_sync_example"'"'")'"

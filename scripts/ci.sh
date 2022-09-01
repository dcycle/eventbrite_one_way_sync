#!/bin/bash
#
# Run tests, meant to be run on CirlceCI.
#
set -e

echo '=> Run fast tests.'
./scripts/test.sh

echo '=> Deploy a Drupal 9 environment.'
./scripts/deploy.sh

echo '=> Tests on Drupal 9 environment.'
./scripts/test-running-environment.sh

echo 'Make sure it is possible to uninstall the module'
docker-compose exec -T drupal /bin/bash -c 'drush pmu -y eventbrite_one_way_sync'

echo '=> Destroy the Drupal 9 environment.'
./scripts/destroy.sh

echo '=> Done with continuous integration tests!'
echo '=>'

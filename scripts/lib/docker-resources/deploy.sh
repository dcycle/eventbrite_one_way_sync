#!/bin/bash
#
# This script is run when the Drupal docker container is ready. It prepares
# an environment for development or testing, which contains a full Drupal
# installation with a running website.
#
set -e

TRIES=20
echo "Server: Will try to connect to MySQL container until it is up. This can take up to $TRIES seconds if the container has just been spun up."
OUTPUT="ERROR"
for i in $(seq 1 "$TRIES");
do
  OUTPUT=$(echo 'show databases'|{ mysql -h mysql -u root --password=drupal 2>&1 || true; })
  if [[ "$OUTPUT" == *"ERROR"* ]]; then
    sleep 1
    echo "Try $i of $TRIES. MySQL container is not available yet. Should not be long..."
  else
    echo "MySQL is up! Moving on..."
    break;
  fi
done

if [[ "$OUTPUT" == *"ERROR"* ]]; then
  >&2 echo "Server could not connect to MySQL after $TRIES tries. Abandoning."
  >&2 echo "$OUTPUT"
  exit 1
fi

cd /var/www/html

echo "Fetch dependencies."
composer require drupal/webhook_receiver

drush si -y --db-url "mysql://root:drupal@mysql/drupal"

echo "Enable our module."
drush en -y eventbrite_one_way_sync_node

echo "Create required content type and field for testing"
mkdir -p /config
drush config:export -y --destination /config
cp /var/www/html/modules/custom/eventbrite_one_way_sync/scripts/lib/self-test-config/*yml /config/
drush config:import -y --source /config

echo "Deployment done on our container."

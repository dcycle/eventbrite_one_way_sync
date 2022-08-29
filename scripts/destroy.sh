#!/bin/bash
#
# Destroy the environment.
#
set -e

docker-compose down -v
docker network rm eventbrite_one_way_sync_default

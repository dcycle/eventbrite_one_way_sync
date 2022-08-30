<?php

namespace Drupal\eventbrite_one_way_sync\Session;

/**
 * A session is associated with a single key.
 */
class SelfTestSession extends SessionBase {

  // This is spun up in ./docker-compose.yml during continuous integration
  // testing.
  const BASE_URL = 'http://dummy_server';

  /**
   * {@inheritdoc}
   */
  public function baseUrl() : string {
    return self::BASE_URL;
  }

}

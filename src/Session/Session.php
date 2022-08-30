<?php

namespace Drupal\eventbrite_one_way_sync\Session;

/**
 * A session is associated with a single key.
 */
class Session extends SessionBase {

  const BASE_URL = 'https://www.eventbriteapi.com/v3';

  /**
   * {@inheritdoc}
   */
  public function baseUrl() : string {
    return self::BASE_URL;
  }

}

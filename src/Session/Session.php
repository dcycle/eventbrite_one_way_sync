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

  /**
   * {@inheritdoc}
   */
  public function getOrganizationId() : string {
    return $this->config()->getOrganizationId($this->eventbriteAccountLabel);
  }

  /**
   * {@inheritdoc}
   */
  public function getPrivateToken() : string {
    return $this->config()->getPrivateToken($this->eventbriteAccountLabel);
  }

}

<?php

namespace Drupal\eventbrite_one_way_sync\Session;

/**
 * A session is associated with a single key.
 */
class SelfTestSession extends SessionBase {

  // This is spun up in ./docker-compose.yml during continuous integration
  // testing.
  const BASE_URL = 'http://dummy_server';
  const ORGANIZATION_ID = 'MY_ORGANIZATION';
  const PRIVATE_TOKEN = 'MY_TOKEN';

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
    return self::ORGANIZATION_ID;
  }

  /**
   * {@inheritdoc}
   */
  public function getPrivateToken() : string {
    return self::PRIVATE_TOKEN;
  }

}

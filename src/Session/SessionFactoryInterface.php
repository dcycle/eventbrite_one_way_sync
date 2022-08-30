<?php

namespace Drupal\eventbrite_one_way_sync\Session;

/**
 * Fetch a session by account label.
 */
interface SessionFactoryInterface {

  /**
   * Get a session.
   *
   * @param string $eventbrite_account_label
   *   An Eventbrite account label such as default.
   *
   * @return \Drupal\eventbrite_one_way_sync\Session\SessionInterface
   *   A session.
   */
  public function get(string $eventbrite_account_label)  : SessionInterface;

}

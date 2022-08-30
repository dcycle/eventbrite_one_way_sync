<?php

namespace Drupal\eventbrite_one_way_sync;

use Drupal\eventbrite_one_way_sync\Session\SessionInterface;

/**
 * Module singleton. Use \Drupal::service('eventbrite_one_way_sync').
 */
interface EventbriteOneWaySyncInterface {

  /**
   * Get a session to connect with an Eventbrite account.
   *
   * @param string $eventbrite_account_label
   *   An Eventbrite account label such as Default.
   *
   * @return \Drupal\eventbrite_one_way_sync\Session\SessionInterface
   *   A session to connect to Eventbrite.
   */
  public function session(string $eventbrite_account_label) : SessionInterface;

}

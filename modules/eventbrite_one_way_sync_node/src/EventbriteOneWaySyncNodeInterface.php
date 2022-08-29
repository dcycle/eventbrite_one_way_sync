<?php

namespace Drupal\eventbrite_one_way_sync_node;

use Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventInterface;

/**
 * EventbriteOneWaySyncNode singleton.
 *
 * Use \Drupal::service('eventbrite_one_way_sync_node').
 */
interface EventbriteOneWaySyncNodeInterface {

  /**
   * Process an event from Eventbrite.
   *
   * @param \Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventInterface $event
   *   An event from Eventbrite.
   */
  public function process(EventbriteEventInterface $event);

}

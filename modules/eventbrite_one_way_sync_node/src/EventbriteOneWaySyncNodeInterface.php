<?php

namespace Drupal\eventbrite_one_way_sync_node;

use Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventValidInterface;

/**
 * EventbriteOneWaySyncNode singleton.
 *
 * Use \Drupal::service('eventbrite_one_way_sync_node').
 */
interface EventbriteOneWaySyncNodeInterface {

  /**
   * Process an event from Eventbrite.
   *
   * @param \Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventValidInterface $event
   *   An event from Eventbrite.
   */
  public function process(EventbriteEventValidInterface $event);

}

<?php

namespace Drupal\eventbrite_one_way_sync\EventbriteEvent;

/**
 * Get eventbrite events.
 */
interface EventbriteEventFactoryInterface {

  /**
   * Given a remote ID, return an Event.
   *
   * @param string $remote_id
   *   A remote id which can exist or not.
   *
   * @return \Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventInterface
   *   An event.
   */
  public function fromRemoteId(string $remote_id) : EventbriteEventInterface;

}

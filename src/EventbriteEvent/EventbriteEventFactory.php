<?php

namespace Drupal\eventbrite_one_way_sync\EventbriteEvent;

/**
 * Get eventbrite events.
 */
class EventbriteEventFactory implements EventbriteEventFactoryInterface {

  /**
   * {@inheritdoc}
   */
  public function fromRemoteId(string $remote_id) : EventbriteEventInterface {
    if (!$remote_id) {
      return new EventbriteEventDoesNotExist();
    }
  }

}

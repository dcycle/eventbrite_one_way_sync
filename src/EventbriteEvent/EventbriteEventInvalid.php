<?php

namespace Drupal\eventbrite_one_way_sync\EventbriteEvent;

/**
 * Represents an Event on Eventbrite.
 */
class EventbriteEventInvalid extends EventbriteEventInQueue {

  /**
   * {@inheritdoc}
   */
  public function process() : bool {
    // This is an invalid event, just remove it.
    $this->removeFromQueue();
    return FALSE;
  }

}

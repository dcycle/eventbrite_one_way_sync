<?php

namespace Drupal\eventbrite_one_way_sync\EventbriteEvent;

/**
 * Represents an Event on Eventbrite.
 */
interface EventbriteEventInterface {

  /**
   * Remove this event from the processing queue.
   */
  public function removeFromQueue();

}

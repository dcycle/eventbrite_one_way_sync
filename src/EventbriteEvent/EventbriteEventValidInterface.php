<?php

namespace Drupal\eventbrite_one_way_sync\EventbriteEvent;

/**
 * Represents a valid Event on Eventbrite.
 */
interface EventbriteEventValidInterface extends EventbriteEventInQueueInterface {

  /**
   * Get the title of this event.
   *
   * @return string
   *   The title of this event.
   */
  public function getTitle() : string;

}

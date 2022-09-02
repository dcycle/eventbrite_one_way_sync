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

  /**
   * Update a struct to represent this event.
   *
   * @param array $struct
   *   A struct to update.
   *
   * @return bool
   *   TRUE if an update has been performed.
   */
  public function toStruct(array &$struct) : bool;

}

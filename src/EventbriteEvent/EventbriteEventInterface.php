<?php

namespace Drupal\eventbrite_one_way_sync\EventbriteEvent;

/**
 * Represents an Event on Eventbrite.
 */
interface EventbriteEventInterface {

  /**
   * Process an event and determine whether to continue or stop all processing.
   *
   * @return bool
   *   TRUE if processing should continue.
   */
  public function process() : bool;

}

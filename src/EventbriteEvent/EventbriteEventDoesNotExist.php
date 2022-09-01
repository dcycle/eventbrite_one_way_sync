<?php

namespace Drupal\eventbrite_one_way_sync\EventbriteEvent;

/**
 * Represents an Event on Eventbrite.
 */
class EventbriteEventDoesNotExist implements EventbriteEventInterface {

  /**
   * {@inheritdoc}
   */
  public function process() : bool {
    return FALSE;
  }

}

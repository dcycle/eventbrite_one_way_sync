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

  /**
   * Get a remote ID such as default:event:123 or default:series:123.
   *
   * @return string
   *   A remote ID such as default:event:123 or default:series:123.
   */
  public function remoteId() : string;

  /**
   * Get the Eventbrite account label.
   *
   * @return string
   *   The Eventbrite account label such as default.
   */
  public function eventbriteAccountLabel() : string;

  /**
   * Get the Eventbrite title.
   *
   * @return string
   *   The Eventbrite title.
   */
  public function getTitle() : string;

}

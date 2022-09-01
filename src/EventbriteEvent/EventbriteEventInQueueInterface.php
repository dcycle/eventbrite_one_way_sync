<?php

namespace Drupal\eventbrite_one_way_sync\EventbriteEvent;

use Drupal\eventbrite_one_way_sync\RemoteId\RemoteIdInterface;

/**
 * Represents an Event on Eventbrite.
 */
interface EventbriteEventInQueueInterface extends EventbriteEventInterface {

  /**
   * Remove this event from the queue in the database.
   */
  public function removeFromQueue();

  /**
   * Get the remote ID as a string.
   *
   * @return \Drupal\eventbrite_one_way_sync\RemoteId\RemoteIdInterface
   *   The remote ID.
   */
  public function remoteId() : RemoteIdInterface;

}

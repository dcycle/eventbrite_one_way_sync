<?php

namespace Drupal\eventbrite_one_way_sync_node\EventNode;

/**
 * An Event node.
 */
interface EventNodeInterface {

  /**
   * Synchronize with the Eventbrite Event.
   */
  public function syncWithEventbriteEvent();

  /**
   * Save the node.
   */
  public function save();

}

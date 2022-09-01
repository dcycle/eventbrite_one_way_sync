<?php

namespace Drupal\eventbrite_one_way_sync_node\EventNode;

/**
 * An Event node.
 */
interface EventNodeInterface {

  /**
   * Synchronize with the Eventbrite Event.
   *
   * @return \Drupal\eventbrite_one_way_sync_node\EventNodeInterface
   *   This object for daisychaining.
   */
  public function syncWithEventbriteEvent() : EventNodeInterface;

  /**
   * Save the node.
   */
  public function save();

}

<?php

namespace Drupal\eventbrite_one_way_sync_node\EventNode;

use Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventInterface;

/**
 * Obtain node objects.
 */
interface NodeFactoryInterface {

  /**
   * Given an event, get or create a Drupal node.
   *
   * @param \Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventInterface $event
   *   An Eventbrite event.
   *
   * @return \Drupal\eventbrite_one_way_sync_node\EventNode\EventNodeInterface
   *   A Drupal node.
   */
  public function getOrCreateNode(EventbriteEventInterface $event) : EventNodeInterface;

}

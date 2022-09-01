<?php

namespace Drupal\eventbrite_one_way_sync_node\EventNode;

use Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventValidInterface;

/**
 * Obtain node objects.
 */
interface NodeFactoryInterface {

  /**
   * Given an event, get or create a Drupal node.
   *
   * @param \Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventValidInterface $event
   *   An Eventbrite event.
   *
   * @return \Drupal\eventbrite_one_way_sync_node\EventNode\EventNodeInterface
   *   A Drupal node.
   */
  public function getOrCreateNode(EventbriteEventValidInterface $event) : EventNodeInterface;

}

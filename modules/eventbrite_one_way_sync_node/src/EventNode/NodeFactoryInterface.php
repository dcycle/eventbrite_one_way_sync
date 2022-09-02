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

  /**
   * Delete all nodes the related to an Eventbrite account.
   *
   * @param string $eventbrite_account_label
   *   An Eventbrite account label, for example "default".
   * @param int $max
   *   The maximum number of nodes to delete. Useful to avoid out-of-memory
   *   issues.
   */
  public function deleteAllNodes(string $eventbrite_account_label, int $max = PHP_INT_MAX);

}

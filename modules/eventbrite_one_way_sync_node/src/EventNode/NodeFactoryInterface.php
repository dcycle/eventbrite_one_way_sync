<?php

namespace Drupal\eventbrite_one_way_sync_node\EventNode;

use Drupal\Core\Entity\EntityInterface;
use Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventValidInterface;
use Drupal\node\NodeInterface;

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

  /**
   * Resave all nodes the related to an Eventbrite account.
   *
   * @param string $eventbrite_account_label
   *   An Eventbrite account label, for example "default".
   * @param int $max
   *   The maximum number of nodes to save. Useful for testing.
   */
  public function resaveAllNodes(string $eventbrite_account_label, int $max = PHP_INT_MAX);

  /**
   * Given a Drupal entity, if it is a valid Eventbrite node, return the node.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   An arbitrary entity.
   * @param array $struct
   *   Array to be populated with the struct, if possible.
   * @param string $eventbrite_account_label
   *   String to be populated with the Eventbrite account label, if possible.
   *
   * @return \Drupal\node\NodeInterface|null
   *   If possible, the valid Eventbrite node.
   */
  public function entityToNodeAndStruct(EntityInterface $entity, array &$struct, string &$eventbrite_account_label) : NodeInterface|null;

}

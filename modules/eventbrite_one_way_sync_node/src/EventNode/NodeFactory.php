<?php

namespace Drupal\eventbrite_one_way_sync_node\EventNode;

use Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventValidInterface;
use Drupal\eventbrite_one_way_sync\Utilities\CommonUtilities;
use Drupal\eventbrite_one_way_sync_node\Utilities\DependencyInjection;
use Drupal\node\NodeInterface;

/**
 * Obtain node objects.
 */
class NodeFactory implements NodeFactoryInterface {

  use DependencyInjection;
  use CommonUtilities;

  /**
   * {@inheritdoc}
   */
  public function getOrCreateNode(EventbriteEventValidInterface $event) : EventNodeInterface {
    $account = $event->remoteId()->eventbriteAccountName();

    $node_type = $this->nodeConfig()->nodeType($account);
    $id_field = $this->nodeConfig()->idField($account);

    $candidate = $this->fetchSingleNode($node_type, $id_field, $event->remoteId()->toString());

    if (!$candidate) {
      $candidate = $this->createNode($node_type, $id_field, $event->remoteId()->toString());
    }

    return new EventNode($candidate, $event);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteAllNodes(string $eventbrite_account_label, int $max = PHP_INT_MAX) {
    $this->assertNonEmptyString($eventbrite_account_label, 'Eventbrite account label cannot be empty');

    $node_type = $this->nodeConfig()->nodeType($eventbrite_account_label);
    $id_field = $this->nodeConfig()->idField($eventbrite_account_label);

    $like = $this->connection()->escapeLike($eventbrite_account_label . ':') . '%';

    print_r('We will attempt to load a maximum of ' . PHP_INT_MAX . ' nodes with ' . $id_field . ' LIKE ' . $like . ':' . PHP_EOL);

    $query = $this->drupalEntityQuery('node');
    $query->condition('type', $node_type);
    $query->condition($id_field, $like, 'LIKE');
    $query->range(0, $max);

    $nids = $query->execute();

    print_r('We found ' . count($nids) . ' nids' . PHP_EOL);

    $nodes = $this->entityTypeManager()->getStorage('node')->loadMultiple($nids);

    $this->entityTypeManager()->getStorage('node')->delete($nodes);

    print_r('We have deleted these ' . count($nids) . ' nodes with ' . $id_field . ' LIKE ' . $like . ':' . PHP_EOL);
    print_r($nids);
  }

  /**
   * Get a single node if it exists.
   *
   * @param string $node_type
   *   A node type such as event.
   * @param string $id_field
   *   An ID field such as field_eventbrite_id.
   * @param string $remoteId
   *   A remote ID such as default:series:123.
   *
   * @return \Drupal\node\NodeInterface|null
   *   A Drupal node if it exists, NULL otherwise.
   */
  public function fetchSingleNode(string $node_type, string $id_field, string $remoteId) : NodeInterface|null {
    $this->assertNonEmptyString($node_type, 'Node type cannot be empty');
    $this->assertNonEmptyString($id_field, 'ID field cannot be empty');
    $this->assertNonEmptyString($remoteId, 'Remote ID cannot be empty');

    $query = $this->drupalEntityQuery('node');
    $query->condition('type', $node_type);
    $query->condition($id_field, $remoteId);
    // We want there to be 0 or 1, but if we get 2 we'll fail.
    $query->range(0, 2);

    $nids = $query->execute();
    $nodes = $this->entityTypeManager()->getStorage('node')->loadMultiple($nids);

    if (count($nodes) == 1) {
      return array_pop($nodes);
    }
    if (count($nodes) == 0) {
      return NULL;
    }

    throw new \Exception('There is more than one node with ' . $id_field . ' set to ' . $remoteId . '; there should not be; please check nodes ' . implode(', ', $nids));
  }

  /**
   * Create a new node.
   *
   * @param string $node_type
   *   A node type such as event.
   * @param string $id_field
   *   An ID field such as field_eventbrite_id.
   * @param string $remoteId
   *   A remote ID such as default:series:123.
   *
   * @return \Drupal\node\NodeInterface
   *   A Drupal node.
   */
  public function createNode(string $node_type, string $id_field, string $remoteId) : NodeInterface {
    $this->assertNonEmptyString($node_type, 'Node type cannot be empty');
    $this->assertNonEmptyString($id_field, 'ID field cannot be empty');
    $this->assertNonEmptyString($remoteId, 'Remote ID cannot be empty');

    $node = $this->entityTypeManager()->getStorage('node')->create([
      'title' => 'New Event',
      'type' => $node_type,
      $id_field => $remoteId,
    ]);
    $node->save();

    return $node;
  }

}

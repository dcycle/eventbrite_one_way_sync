<?php

namespace Drupal\eventbrite_one_way_sync_node\EventNode;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Entity\EntityInterface;
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
  public function entityToNodeAndStruct(EntityInterface $entity, array &$struct, string &$eventbrite_account_label) : NodeInterface|null {
    if ($entity->getEntityType()->id() != 'node') {
      return NULL;
    }

    // At this point we have a node. Just make sure.
    if ($entity instanceof NodeInterface) {
      foreach ($this->nodeConfig()->getFieldMapping() as $account_label => $info) {
        if ($entity->getType() == $info['node_type']) {
          $field_values = $entity->get($info['id_field'])->getValue();

          if (isset($field_values[0]['value']) && substr($field_values[0]['value'], 0, strlen($account_label . ':')) == $account_label . ':') {
            // We have a hit.
            $eventbrite_account_label = $account_label;
            $struct = $this->nodeStruct($entity, $info['struct_field']);
            return $entity;
          }
        }
      }
    }

    // This entity is not an Eventbrite node.
    return NULL;
  }

  /**
   * Given a node and field, return a struct.
   *
   * @param \Drupal\node\NodeInterface $node
   *   A node.
   * @param string $field_name
   *   A field name.
   *
   * @return array
   *   A struct.
   */
  public function nodeStruct(NodeInterface $node, string $field_name) : array {
    $field_values = $node->get($field_name)->getValue();
    $ret = [];

    if (isset($field_values[0]['value'])) {
      $ret = Json::decode($field_values[0]['value']);
    }

    return is_array($ret) ? $ret : [];
  }

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
  public function resaveAllNodes(string $eventbrite_account_label, int $max = PHP_INT_MAX) {
    $nids = $this->getAllNids($eventbrite_account_label, $max);

    print_r('We found ' . count($nids) . ' nids' . PHP_EOL);

    $nodes = $this->entityTypeManager()->getStorage('node')->loadMultiple($nids);

    foreach ($nodes as $node) {
      $node->save();
    }
  }

  /**
   * Get all nids the related to an Eventbrite account.
   *
   * @param string $eventbrite_account_label
   *   An Eventbrite account label, for example "default".
   * @param int $max
   *   The maximum number of nodes to save. Useful for testing.
   */
  public function getAllNids(string $eventbrite_account_label, int $max = PHP_INT_MAX) : array {
    $this->assertNonEmptyString($eventbrite_account_label, 'Eventbrite account label cannot be empty');

    $node_type = $this->nodeConfig()->nodeType($eventbrite_account_label);
    $id_field = $this->nodeConfig()->idField($eventbrite_account_label);

    $like = $this->connection()->escapeLike($eventbrite_account_label . ':') . '%';

    print_r('We will attempt to load a maximum of ' . PHP_INT_MAX . ' nodes with ' . $id_field . ' LIKE ' . $like . ':' . PHP_EOL);

    $query = $this->drupalEntityQuery('node');
    $query->accessCheck(FALSE);
    $query->condition('type', $node_type);
    $query->condition($id_field, $like, 'LIKE');
    $query->range(0, $max);

    return $query->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function deleteAllNodes(string $eventbrite_account_label, int $max = PHP_INT_MAX) {
    $nids = $this->getAllNids($eventbrite_account_label, $max);

    print_r('We found ' . count($nids) . ' nids' . PHP_EOL);

    $nodes = $this->entityTypeManager()->getStorage('node')->loadMultiple($nids);

    $this->entityTypeManager()->getStorage('node')->delete($nodes);

    print_r('We have deleted these ' . count($nids) . ' nodes:' . PHP_EOL);
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
    $query->accessCheck(FALSE);
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

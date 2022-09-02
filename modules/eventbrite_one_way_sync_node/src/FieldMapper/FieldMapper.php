<?php

namespace Drupal\eventbrite_one_way_sync_node\FieldMapper;

use Drupal\eventbrite_one_way_sync_node\Utilities\DependencyInjection;
use Drupal\Core\Entity\EntityInterface;
use Drupal\node\NodeInterface;

/**
 * Maps field on node save.
 */
class FieldMapper implements FieldMapperInterface {

  use DependencyInjection;

  /**
   * {@inheritdoc}
   */
  public function hookEntityPresave(EntityInterface $entity) {
    try {
      $struct = [];
      $eventbrite_account_label = '';

      if ($node = $this->nodeFactory()->entityToNodeAndStruct($entity, $struct, $eventbrite_account_label)) {
        // At this point the node is a valid node, we can now do our mapping.
        $this->mapTitle($node, $struct, $eventbrite_account_label);
        $this->mapDates($node, $struct, $eventbrite_account_label);
      }
    }
    catch (\Throwable $t) {
      // In case of any exception, do not completely break the workflow.
      $this->errorLogger()->logThrowable($t);
    }
  }

  /**
   * Map the title.
   *
   * @param \Drupal\node\NodeInterface $node
   *   A node.
   * @param array $struct
   *   An eventbrite struct.
   * @param string $eventbrite_account_label
   *   The Eventbrite account label.
   */
  public function mapTitle(NodeInterface $node, array $struct, string $eventbrite_account_label) {
    $first = array_shift($struct);
    if (!empty($first['name']['text']) && is_string($first['name']['text'])) {
      $node->setTitle($first['name']['text']);
    }
  }

  /**
   * Map the dates.
   *
   * @param \Drupal\node\NodeInterface $node
   *   A node.
   * @param array $struct
   *   An eventbrite struct.
   * @param string $eventbrite_account_label
   *   The Eventbrite account label.
   */
  public function mapDates(NodeInterface $node, array $struct, string $eventbrite_account_label) {
    $date_field = $this->nodeConfig()->dateField($eventbrite_account_label);

    foreach ($struct as $eventbrite_event) {
      $this->addDateFromEventbriteEvent($eventbrite_event, $node, $date_field);
    }
  }

  /**
   * Map a single date.
   *
   * @param array $eventbrite_event
   *   An eventbrite event.
   * @param \Drupal\node\NodeInterface $node
   *   A node.
   * @param string $date_field
   *   A date field to use.
   */
  public function addDateFromEventbriteEvent(array $eventbrite_event, NodeInterface $node, string $date_field) {
    // We store our dates in UTC.
    $start = $eventbrite_event['start']['utc'] ?? '';
    $end = $eventbrite_event['end']['utc'] ?? '';

    if (!$start || !$end) {
      return;
    }

    // Remove the timezone indicator.
    $start = str_replace('Z', '', $start);
    $end = str_replace('Z', '', $end);

    // Perhaps this event exists in the dates already.
    if ($this->exists($node, $start, $end, $date_field)) {
      return;
    }

    $field_val = $node->get($date_field)->getValue();
    $field_val = array_merge($field_val, [$this->toDrupalFormat($start, $end)]);

    $node->{$date_field} = $field_val;
  }

  /**
   * A date in Drupal format.
   *
   * @param string $start
   *   Start date.
   * @param string $end
   *   End date.
   *
   * @return array
   *   Suitable for Drupal field.
   */
  public function toDrupalFormat(string $start, string $end) : array {
    return [
      'value' => $start,
      'end_value' => $end,
    ];
  }

  /**
   * Does a date already exist in the node.
   *
   * @param \Drupal\node\NodeInterface $node
   *   Node.
   * @param string $start
   *   Start date.
   * @param string $end
   *   End date.
   * @param string $date_field
   *   A date field.
   *
   * @return bool
   *   TRUE if exists.
   */
  public function exists(NodeInterface $node, string $start, string $end, string $date_field) : bool {
    $field_val = $node->get($date_field)->getValue();

    foreach ($field_val as $single_field_val) {
      if ($start == $single_field_val['value'] && $end == $single_field_val['end_value']) {
        return TRUE;
      }
    }

    return FALSE;
  }

}

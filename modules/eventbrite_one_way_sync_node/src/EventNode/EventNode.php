<?php

namespace Drupal\eventbrite_one_way_sync_node\EventNode;

use Drupal\node\NodeInterface;
use Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventValidInterface;
use Drupal\Component\Serialization\Json;
use Drupal\eventbrite_one_way_sync_node\Utilities\DependencyInjection;

/**
 * An Event node.
 */
class EventNode implements EventNodeInterface {

  use DependencyInjection;

  /**
   * The Drupal node.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $node;

  /**
   * The Eventbrite event.
   *
   * @var \Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventValidInterface
   */
  protected $event;

  /**
   * Constructor.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The Drupal node.
   * @param \Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventValidInterface $event
   *   The Eventbrite event.
   */
  public function __construct(NodeInterface $node, EventbriteEventValidInterface $event) {
    $this->node = $node;
    $this->event = $event;
  }

  /**
   * {@inheritdoc}
   */
  public function syncWithEventbriteEvent() : EventNodeInterface {
    $this->node->setTitle($this->event->getTitle());
    $this->populateStruct();
    return $this;
  }

  /**
   * Populate a node's struct field with Eventbrite struct.
   */
  public function populateStruct() {
    $struct = $this->struct();
    $this->event->toStruct($struct);
    $this->node->set($this->structField(), JSON::encode($struct));
  }

  /**
   * Get the Eventbrite account label.
   *
   * @return string
   *   The Eventbrite account label.
   */
  public function eventbriteAccountLabel() : string {
    return $this->event->remoteId()->eventbriteAccountName();
  }

  /**
   * Get the struct field name.
   *
   * @return string
   *   The struct field name.
   */
  public function structField() : string {
    return $this->nodeConfig()->structField($this->eventbriteAccountLabel());
  }

  /**
   * Get the existing Eventbrite struct.
   *
   * @return array
   *   Array representing this event series.
   */
  public function struct() : array {
    $candidate = JSON::decode($this->fieldValue($this->structField()));
    return is_array($candidate) ? $candidate : [];
  }

  /**
   * Get the first field value of a field as a string.
   *
   * @param string $field
   *   A field name.
   *
   * @return string
   *   A field value.
   */
  public function fieldValue(string $field) : string {
    $value = $this->node->get($field)->getValue();

    if (count($value) && array_key_exists('value', $value[0])) {
      return $value[0]['value'];
    }

    return '';
  }

  /**
   * {@inheritdoc}
   */
  public function save() {
    $this->node->save();
  }

}

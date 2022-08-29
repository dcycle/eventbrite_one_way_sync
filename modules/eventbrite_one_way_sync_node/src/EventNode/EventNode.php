<?php

namespace Drupal\eventbrite_one_way_sync_node\EventNode;

use Drupal\node\NodeInterface;
use Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventInterface;

/**
 * An Event node.
 */
class EventNode implements EventNodeInterface {

  /**
   * The Drupal node.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $node;

  /**
   * The Eventbrite event.
   *
   * @var \Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventInterface
   */
  protected $event;

  /**
   * Constructor.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The Drupal node.
   * @param \Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventInterface $event
   *   The Eventbrite event.
   */
  public function __construct(NodeInterface $node, EventbriteEventInterface $event) {
    $this->node = $node;
    $this->event = $event;
  }

  /**
   * {@inheritdoc}
   */
  public function syncWithEventbriteEvent() {
    $this->node->setTitle($this->event->getTitle());
  }

  /**
   * {@inheritdoc}
   */
  public function save() {
    $this->node->save();
  }

}

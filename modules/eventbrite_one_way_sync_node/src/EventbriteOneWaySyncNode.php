<?php

namespace Drupal\eventbrite_one_way_sync_node;

use Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventValidInterface;
use Drupal\eventbrite_one_way_sync_node\Utilities\DependencyInjection;

/**
 * EventbriteOneWaySyncNode singleton.
 *
 * Use \Drupal::service('eventbrite_one_way_sync_node').
 */
class EventbriteOneWaySyncNode implements EventbriteOneWaySyncNodeInterface {

  use DependencyInjection;

  /**
   * {@inheritdoc}
   */
  public function process(EventbriteEventValidInterface $event) {
    $this->nodeFactory()
      ->getOrCreateNode($event)
      ->syncWithEventbriteEvent()
      ->save();

    $event->removeFromQueue();
  }

}

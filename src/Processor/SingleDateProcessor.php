<?php

namespace Drupal\eventbrite_one_way_sync\Processor;

/**
 * Processor for a single-date event.
 */
class SingleDateProcessor extends ProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function recordTypeAndId(): string {
    return 'event:' . $this->eventId();
  }

}

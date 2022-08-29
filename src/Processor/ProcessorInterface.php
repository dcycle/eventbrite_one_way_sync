<?php

namespace Drupal\eventbrite_one_way_sync\Processor;

/**
 * A processor for a struct.
 *
 * The processor's job is to take an event and put it into the queue, which
 * has these fields:
 * * event_id.
 */
interface ProcessorInterface {

  /**
   * Process this record.
   */
  public function process();

}

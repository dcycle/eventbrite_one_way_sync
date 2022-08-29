<?php

namespace Drupal\eventbrite_one_way_sync\Processor;

/**
 * Processor for a series.
 */
class SeriesProcessor extends ProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function recordTypeAndId(): string {
    return 'series:' . $this->seriesId();
  }

  /**
   * Get the ID of this series.
   *
   * @return string
   *   The ID of this series.
   */
  public function seriesId() : string {
    return $this->struct['series_id'];
  }

}

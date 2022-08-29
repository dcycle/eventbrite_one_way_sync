<?php

namespace Drupal\eventbrite_one_way_sync\Processor;

use Drupal\eventbrite_one_way_sync\Utilities\CommonUtilities;
use Drupal\eventbrite_one_way_sync\Utilities\DependencyInjection;

/**
 * Obtain a processor for a struct.
 */
class ProcessorFactory implements ProcessorFactoryInterface {

  use CommonUtilities;
  use DependencyInjection;

  /**
   * {@inheritdoc}
   */
  public function getProcessor(string $eventbrite_account_label, array $struct, callable $log) : ProcessorInterface {
    $this->assertNonEmptyString($eventbrite_account_label, 'Eventbrite account label cannot be empty');

    if (array_key_exists('is_series', $struct) && $struct['is_series']) {
      return new SeriesProcessor($eventbrite_account_label, $struct, $log);
    }
    else {
      return new SingleDateProcessor($eventbrite_account_label, $struct, $log);
    }
  }

}

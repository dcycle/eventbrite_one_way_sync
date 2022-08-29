<?php

namespace Drupal\eventbrite_one_way_sync\Processor;

/**
 * Obtain a processor for a struct.
 */
interface ProcessorFactoryInterface {

  /**
   * Get the processor to use for a specific account and struct.
   *
   * @param string $eventbrite_account_label
   *   An Eventbrite account label such as "default".
   * @param array $struct
   *   A struct coming from Eventbrite.
   * @param callable $log
   *   Where to log progress.
   *
   * @return \Drupal\eventbrite_one_way_sync\Processor\ProcessorInterface
   *   The processor to use.
   */
  public function getProcessor(string $eventbrite_account_label, array $struct, callable $log) : ProcessorInterface;

}

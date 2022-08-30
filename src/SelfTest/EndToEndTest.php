<?php

namespace Drupal\eventbrite_one_way_sync\SelfTest;

use Drupal\eventbrite_one_way_sync\Utilities\DependencyInjection;

/**
 * Test the whole system, called by ./scripts/test-running-environment.sh.
 */
class EndToEndTest implements EndToEndTestInterface {

  use DependencyInjection;

  /**
   * {@inheritdoc}
   */
  public function run(callable|null $log = NULL) {
    if (!$log) {
      $log = function ($x) {
        print_r($x . PHP_EOL);
      };
    }

    $log('Starting end-to-end test');

    $dummy_eventbrite_id = $this->config()->selfTestDummyAccount();

    $log('Run smoke test on dummy account');

    $this->smokeTest()->run($dummy_eventbrite_id);

    $log('Import existing to queue');

    $this->app()->session($dummy_eventbrite_id)
      ->importExistingToQueue();
  }

}

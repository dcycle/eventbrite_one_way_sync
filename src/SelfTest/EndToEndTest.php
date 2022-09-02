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

    $this->idempotentImport(1, $log);
    $this->idempotentImport(2, $log);

    $log('Run webhook test');

    $this->webhookReceiverRequestResponseTest()->run('eventbrite_one_way_sync');

    $log('Confirm queue has 4 items');

    $this->assertQueue(4);

    $log('Run cron');

    $this->app()->hookCron();

    $log('Confirm queue has zero items');

    $this->assertQueue(0);

    $this->idempotentImport(3, $log);
    $this->idempotentImport(4, $log);
    $this->webhookReceiverRequestResponseTest()->run('eventbrite_one_way_sync');

    $log('Confirm queue has 4 items');

    $this->assertQueue(4);

    $log('Run cron');

    $this->app()->hookCron();

    $log('Confirm queue has zero items');

    $this->assertQueue(0);
  }

  /**
   * Throw an exception if there are not a specific number of items in queue.
   *
   * @param int $cycle
   *   Which count are we at.
   * @param callable $log
   *   A logging function.
   */
  public function idempotentImport(int $cycle, callable $log) {
    $dummy_eventbrite_id = $this->config()->selfTestDummyAccount();

    $log('Import existing to queue (try ' . $cycle . ')');

    $this->app()->session($dummy_eventbrite_id)
      ->importExistingToQueue();

    $log('Confirm queue has 4 items');

    $this->assertQueue(4);
  }

  /**
   * Throw an exception if there are not a specific number of items in queue.
   *
   * @param int $expected_count
   *   The number of items we are expecing.
   */
  public function assertQueue(int $expected_count) {
    $count = $this->database()->countQueue();
    if ($count != $expected_count) {
      throw new \Exception('The queue has ' . $count . ' items, not the expected ' . $expected_count);
    }
  }

}

<?php

namespace Drupal\eventbrite_one_way_sync\SelfTest;

/**
 * Test the whole system, called by ./scripts/test-running-environment.sh.
 */
interface EndToEndTestInterface {

  /**
   * Run the end-to-end tests.
   *
   * @param callable|null $log
   *   A logging function.
   */
  public function run(callable|null $log = NULL);

}

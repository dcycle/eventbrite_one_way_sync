<?php

namespace Drupal\eventbrite_one_way_sync\SmokeTest;

/**
 * Runs smoke tests on your configuration.
 */
interface SmokeTestInterface {

  /**
   * Run the smoke test.
   *
   * Meant to be run on the command line.
   *
   * @param string $eventbrite_account_key
   *   See "What are keys" in ./README.md.
   */
  public function run(string $eventbrite_account_key);

}

<?php

namespace Drupal\eventbrite_one_way_sync\SmokeTest;

use Drupal\eventbrite_one_way_sync\SelfTest\SelfTestLogTrait;
use Drupal\eventbrite_one_way_sync\Utilities\DependencyInjection;
use Drupal\eventbrite_one_way_sync\Utilities\CommonUtilities;

/**
 * Makes sure your configuration works.
 */
class SmokeTest implements SmokeTestInterface {

  use CommonUtilities;
  use DependencyInjection;
  use SelfTestLogTrait;

  /**
   * {@inheritdoc}
   */
  public function run(string $eventbrite_account_label) {
    try {
      $this->log('Making sure the Eventbrite account ' . $eventbrite_account_label . ' has an associated API key.');
      $this->config()->getPrivateToken($eventbrite_account_label);
      $this->log('All good.');
      $this->log('Making sure we can access Eventbrite using our key.');
      $this->app()->session($eventbrite_account_label)->smokeTest();
      $this->log('All good.');

      $this->plugins()->smokeTest($eventbrite_account_label);
    }
    catch (\Throwable $t) {
      $this->err($this->throwableToString($t));
    }
  }

}

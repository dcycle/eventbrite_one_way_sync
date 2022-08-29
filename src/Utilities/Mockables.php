<?php

namespace Drupal\eventbrite_one_way_sync\Utilities;

use Drupal\Core\Access\AccessResult;
// @codingStandardsIgnoreStart
use Drupal\eventbrite_one_way_sync\EventbriteOneWaySync;
// @codingStandardsIgnoreEnd

/**
 * A quick way to fetch mockable service singletons.
 */
trait Mockables {

  /**
   * Mockable wrapper around AccessResult::allowed().
   */
  public function accessAllowed() {
    return AccessResult::allowed();
  }

  /**
   * Mockable wrapper around AccessResult::forbidden().
   */
  public function accessForbidden() {
    return AccessResult::forbidden();
  }

}

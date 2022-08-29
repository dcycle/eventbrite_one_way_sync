<?php

namespace Drupal\eventbrite_one_way_sync;

use Drupal\eventbrite_one_way_sync\Utilities\DependencyInjection;
use Drupal\eventbrite_one_way_sync\Session\SessionInterface;
use Drupal\eventbrite_one_way_sync\Session\Session;

/**
 * Module singleton. Use \Drupal::service('eventbrite_one_way_sync').
 */
class EventbriteOneWaySync implements EventbriteOneWaySyncInterface {

  use DependencyInjection;

  /**
   * {@inheritdoc}
   */
  public function session(string $eventbrite_account_label) : SessionInterface {
    return new Session($eventbrite_account_label);
  }

}

<?php

namespace Drupal\eventbrite_one_way_sync;

use Drupal\eventbrite_one_way_sync\Utilities\DependencyInjection;
use Drupal\eventbrite_one_way_sync\Session\SessionInterface;

/**
 * Module singleton. Use \Drupal::service('eventbrite_one_way_sync').
 */
class EventbriteOneWaySync implements EventbriteOneWaySyncInterface {

  use DependencyInjection;

  /**
   * {@inheritdoc}
   */
  public function session(string $eventbrite_account_label) : SessionInterface {
    return $this->sessionFactory()->get($eventbrite_account_label);
  }

  /**
   * {@inheritdoc}
   */
  public function endToEndTest() {
    $dummy_eventbrite_id = $this->config()->selfTestDummyAccount();

    $this->smokeTest()->run($dummy_eventbrite_id);
    $this->session($dummy_eventbrite_id)
      ->importExistingToQueue();
  }

}

<?php

namespace Drupal\eventbrite_one_way_sync;

use Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventInterface;

/**
 * An interface for all EventbriteOneWaySyncPlugin type plugins.
 *
 * This is based on code from the Examples module.
 */
interface EventbriteOneWaySyncPluginInterface {

  /**
   * Process an event from Eventbrite.
   *
   * @param \Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventInterface $event
   *   An event from Eventbrite.
   */
  public function process(EventbriteEventInterface $event);

  /**
   * Run smoke test.
   *
   * @param string $eventbrite_account_label
   *   The Eventbrite account, for example "default".
   */
  public function smokeTest(string $eventbrite_account_label);

}

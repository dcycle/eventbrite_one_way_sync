<?php

namespace Drupal\eventbrite_one_way_sync;

use Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventValidInterface;

/**
 * An interface for all EventbriteOneWaySyncPlugin type plugins.
 *
 * This is based on code from the Examples module.
 */
interface EventbriteOneWaySyncPluginInterface {

  /**
   * Process an event from Eventbrite.
   *
   * @param \Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventValidInterface $event
   *   An event from Eventbrite.
   */
  public function process(EventbriteEventValidInterface $event);

  /**
   * Run smoke test.
   *
   * @param string $eventbrite_account_label
   *   The Eventbrite account, for example "default".
   */
  public function smokeTest(string $eventbrite_account_label);

}

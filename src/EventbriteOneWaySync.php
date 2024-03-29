<?php

namespace Drupal\eventbrite_one_way_sync;

use Drupal\eventbrite_one_way_sync\Session\SessionInterface;
use Drupal\eventbrite_one_way_sync\Utilities\DependencyInjection;

/**
 * Module singleton. Use \Drupal::service('eventbrite_one_way_sync').
 */
class EventbriteOneWaySync implements EventbriteOneWaySyncInterface {

  use DependencyInjection;

  const MAX_CRON_EXECUTION = 120;

  /**
   * {@inheritdoc}
   */
  public function session(string $eventbrite_account_label) : SessionInterface {
    return $this->sessionFactory()->get($eventbrite_account_label);
  }

  /**
   * {@inheritdoc}
   */
  public function hookCron() {
    $start = $this->time()->getCurrentTime();
    while ($this->time()->getCurrentTime() - $start <= self::MAX_CRON_EXECUTION) {
      if (!$this->processNext()) {
        break;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function processNext() : bool {
    return $this->database()
      ->nextEvent()
      ->process();
  }

  /**
   * {@inheritdoc}
   */
  public function webhook(string $eventbrite_account_label) : string {
    return $this->webhookReceiver()->webhooks()['eventbrite_one_way_sync']['webhook_path'] . '?eventbrite_account_label=' . $eventbrite_account_label;
  }

}

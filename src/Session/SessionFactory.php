<?php

namespace Drupal\eventbrite_one_way_sync\Session;

use Drupal\eventbrite_one_way_sync\Utilities\CommonUtilities;
use Drupal\eventbrite_one_way_sync\Utilities\DependencyInjection;

/**
 * Fetch a session by account label.
 */
class SessionFactory implements SessionFactoryInterface {

  use CommonUtilities;
  use DependencyInjection;

  /**
   * {@inheritdoc}
   */
  public function get(string $eventbrite_account_label)  : SessionInterface {
    $this->assertNonEmptyString($eventbrite_account_label, 'Account label must be non-empty');

    if ($eventbrite_account_label == $this->config()->selfTestDummyAccount()) {
      return new SelfTestSession($eventbrite_account_label);
    }

    return new Session($eventbrite_account_label);
  }

}

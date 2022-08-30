<?php

namespace Drupal\eventbrite_one_way_sync\Session;

use Drupal\eventbrite_one_way_sync\Utilities\CommonUtilities;

/**
 * Fetch a session by account label.
 */
class SessionFactory implements SessionFactoryInterface {

  use CommonUtilities;

  const SELFTEST_ACCOUNT = 'selftest';

  /**
   * {@inheritdoc}
   */
  public function get(string $eventbrite_account_label)  : SessionInterface {
    $this->assertNonEmptyString($eventbrite_account_label, 'Account label must be non-empty');

    if ($eventbrite_account_label == self::SELFTEST_ACCOUNT) {
      return new SelfTestSession($eventbrite_account_label);
    }

    return new Session($eventbrite_account_label);
  }

}

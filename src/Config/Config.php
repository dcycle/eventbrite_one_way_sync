<?php

namespace Drupal\eventbrite_one_way_sync\Config;

use Drupal\eventbrite_one_way_sync\Utilities\Singleton;
use Drupal\eventbrite_one_way_sync\Utilities\CommonUtilities;
use Drupal\eventbrite_one_way_sync\Utilities\DependencyInjection;

/**
 * Wrapper around configuration.
 */
class Config implements ConfigInterface {

  use Singleton;
  use CommonUtilities;
  use DependencyInjection;

  /**
   * {@inheritdoc}
   */
  public function allApiKeys() : array {
    $candidate = $this->configFactory()
      ->get('eventbrite_one_way_sync.unversioned')
      ->get('api-keys');

    $this->assertNonEmptyArray($candidate, "The API keys in your settings.php file should be a non-empty array, please see eventbrite_one_way_sync's ./README.md file.");

    return $candidate;
  }

  /**
   * {@inheritdoc}
   */
  public function getPrivateToken(string $eventbrite_account_label) : string {
    return $this->apiInfo($eventbrite_account_label, 'private_token');
  }

  /**
   * {@inheritdoc}
   */
  public function getOrganizationId(string $eventbrite_account_label) : string {
    return $this->apiInfo($eventbrite_account_label, 'organization_id');
  }

  /**
   * Get API info (e.g. organization id, private token) associated with account.
   *
   * @param string $eventbrite_account_label
   *   Eventbrite account label, for example "default".
   * @param string $info
   *   Requested info, for example private_token or organization_id.
   *
   * @return string
   *   Value of the requested info.
   */
  public function apiInfo(string $eventbrite_account_label, string $info) : string {
    $this->assertNonEmptyString($eventbrite_account_label, 'Account label cannot be empty');
    $this->assertNonEmptyString($info, 'Info cannot be empty');

    $config = $this->allApiKeys();

    if (!is_array($config)) {
      throw new \Exception('The config for eventbrite_one_way_sync.settings.api-keys is not an array, please see README.md for eventbrite_one_way_sync on how to set up the api keys.');
    }

    if (!array_key_exists($eventbrite_account_label, $config)) {
      throw new \Exception('There is no key with the label ' . $eventbrite_account_label . " in eventbrite_one_way_sync's configuration.");
    }

    if (!is_array($config[$eventbrite_account_label])) {
      throw new \Exception('The API key ' . $eventbrite_account_label . ' is not an array (eventbrite_one_way_sync).');
    }

    if (!$config[$eventbrite_account_label][$info]) {
      throw new \Exception('The API key ' . $eventbrite_account_label . '[' . $info . '] is empty (eventbrite_one_way_sync).');
    }

    $this->assertNonEmptyString($config[$eventbrite_account_label][$info], 'Api key info ' . $eventbrite_account_label . ' [' . $info . '] must be a string.');

    return $config[$eventbrite_account_label][$info];
  }

}

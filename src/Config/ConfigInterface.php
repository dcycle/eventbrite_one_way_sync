<?php

namespace Drupal\eventbrite_one_way_sync\Config;

/**
 * Wrapper around configuration.
 */
interface ConfigInterface {

  /**
   * Get all API keys from the settings.php file. See ./README.md.
   *
   * @return array
   *   All API keys keyed by label.
   */
  public function allApiKeys() : array;

  /**
   * Get the private token associated with an account.
   *
   * @param string $eventbrite_account_label
   *   A label, for example "default", useful if you have more than one API key.
   *
   * @return string
   *   An API key.
   */
  public function getPrivateToken(string $eventbrite_account_label) : string;

  /**
   * Get the organization ID associated with an account.
   *
   * @param string $eventbrite_account_label
   *   A label, for example "default", useful if you have more than one API key.
   *
   * @return string
   *   An API key.
   */
  public function getOrganizationId(string $eventbrite_account_label) : string;

}

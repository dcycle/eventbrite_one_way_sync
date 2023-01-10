<?php

namespace Drupal\eventbrite_one_way_sync;

use Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventValidInterface;
use Drupal\eventbrite_one_way_sync\Utilities\DependencyInjection;

/**
 * Abstraction around a collection of plugins.
 */
class EventbriteOneWaySyncPluginCollection implements EventbriteOneWaySyncPluginInterface, \Countable {

  use DependencyInjection;

  /**
   * Get plugin objects.
   *
   * @param bool $reset
   *   Whether to re-fetch plugins; otherwise we use the static variable.
   *   This can be useful during testing.
   *
   * @return array
   *   Array of plugin objects.
   *
   * @throws \Exception
   */
  public function plugins(bool $reset = FALSE) : array {
    static $return = NULL;

    if ($return === NULL || $reset) {
      $return = [];
      foreach (array_keys($this->pluginDefinitions()) as $plugin_id) {
        $return[$plugin_id] = $this->byId($plugin_id);
      }
    }

    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function count() : int {
    return count($this->pluginDefinitions());
  }

  /**
   * Get plugin definitions based on their annotations.
   *
   * @return array
   *   Array of plugin definitions.
   *
   * @throws \Exception
   */
  public function pluginDefinitions() : array {
    $return = $this->pluginManager()->getDefinitions();

    uasort($return, function (array $a, array $b) : int {
      if ($a['weight'] == $b['weight']) {
          return 0;
      }
      return ($a['weight'] < $b['weight']) ? -1 : 1;
    });

    return $return;
  }

  /**
   * Get an array of example URLs for usage.
   *
   * @param string $base_url
   *   The base URL to use for the examples.
   * @param string $token
   *   A token which should be used for the examples.
   *
   * @return array
   *   Array of example URLs for usage.
   *
   * @throws \Exception
   */
  public function exampleUrls(string $base_url, string $token) : array {
    $return = [];

    foreach ($this->pluginDefinitions() as $pluginDefinition) {
      foreach ($pluginDefinition['examples'] as $example) {
        $return[] = str_replace('[url]', $base_url, str_replace('[token]', $token, $example));
      }
    }

    return $return;
  }

  /**
   * Get a single plugin by its id.
   *
   * @param string $plugin_id
   *   The plugin id.
   *
   * @return \Drupal\eventbrite_one_way_sync\EventbriteOneWaySyncPluginInterface
   *   The plugin.
   */
  public function byId(string $plugin_id) : EventbriteOneWaySyncPluginInterface {
    return $this->pluginManager()->createInstance($plugin_id, ['of' => 'configuration values']);
  }

  /**
   * {@inheritdoc}
   */
  public function process(EventbriteEventValidInterface $event) {
    foreach ($this->plugins() as $plugin) {
      $plugin->process($event);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function smokeTest(string $eventbrite_account_label) {
    foreach ($this->plugins() as $plugin) {
      $plugin->smokeTest($eventbrite_account_label);
    }
  }

}

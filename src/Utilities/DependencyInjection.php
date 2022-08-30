<?php

namespace Drupal\eventbrite_one_way_sync\Utilities;

use Drupal\eventbrite_one_way_sync\Config\ConfigInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\eventbrite_one_way_sync\SmokeTest\SmokeTestInterface;
use Drupal\eventbrite_one_way_sync\EventbriteOneWaySyncInterface;
use Drupal\eventbrite_one_way_sync\Processor\ProcessorFactoryInterface;
use Drupal\eventbrite_one_way_sync\Database\DatabaseInterface;
use Drupal\Core\Database\Connection;
use Drupal\eventbrite_one_way_sync\EventbriteOneWaySyncPluginCollection;
use Drupal\eventbrite_one_way_sync\EventbriteOneWaySyncPluginManager;
use Drupal\eventbrite_one_way_sync\Session\SessionFactoryInterface;

/**
 * I like using a trait rather than services arguments which I find messy.
 */
trait DependencyInjection {

  /**
   * Get the plugins.
   *
   * @return \Drupal\eventbrite_one_way_sync\EventbriteOneWaySyncPluginCollection
   *   The plugins.
   */
  public function plugins() : EventbriteOneWaySyncPluginCollection {
    return \Drupal::service('eventbrite_one_way_sync.plugins');
  }

  /**
   * Get our plugin manager.
   *
   * @return \Drupal\eventbrite_one_way_sync\EventbriteOneWaySyncPluginManager
   *   The plugin manager.
   */
  public function pluginManager() : EventbriteOneWaySyncPluginManager {
    return \Drupal::service('plugin.manager.eventbrite_one_way_sync');
  }

  /**
   * Get the config factory service.
   *
   * @return \Drupal\Core\Config\ConfigFactoryInterface
   *   The config factory.
   */
  public function configFactory() : ConfigFactoryInterface {
    return \Drupal::service('config.factory');
  }

  /**
   * Get the session factory service.
   *
   * @return \Drupal\eventbrite_one_way_sync\Session\SessionFactoryInterface
   *   The session factory.
   */
  public function sessionFactory() : SessionFactoryInterface {
    return \Drupal::service('eventbrite_one_way_sync.session_factory');
  }

  /**
   * Get the config service.
   *
   * @return \Drupal\eventbrite_one_way_sync\Config\ConfigInterface
   *   The config factory.
   */
  public function config() : ConfigInterface {
    return \Drupal::service('eventbrite_one_way_sync.config');
  }

  /**
   * Get the smoke test service.
   *
   * @return \Drupal\eventbrite_one_way_sync\SmokeTest\SmokeTestInterface
   *   The smoke test.
   */
  public function smokeTest() : SmokeTestInterface {
    return \Drupal::service('eventbrite_one_way_sync.smoke_test');
  }

  /**
   * Get the database service.
   *
   * @return \Drupal\eventbrite_one_way_sync\Database\DatabaseInterface
   *   The database service.
   */
  public function database() : DatabaseInterface {
    return \Drupal::service('eventbrite_one_way_sync.database');
  }

  /**
   * Get the processor factory service.
   *
   * @return \Drupal\eventbrite_one_way_sync\Processor\ProcessorFactoryInterface
   *   The processor factory.
   */
  public function processorFactory() : ProcessorFactoryInterface {
    return \Drupal::service('eventbrite_one_way_sync.processor_factory');
  }

  /**
   * Get the app service.
   *
   * @return \Drupal\eventbrite_one_way_sync\EventbriteOneWaySyncInterface
   *   The app.
   */
  public function app() : EventbriteOneWaySyncInterface {
    return \Drupal::service('eventbrite_one_way_sync');
  }

  /**
   * Get the entity type manager service.
   *
   * @return \Drupal\Core\Entity\EntityTypeManagerInterface
   *   The entity type manager.
   */
  public function entityTypeManager() : EntityTypeManagerInterface {
    return \Drupal::service('entity_type.manager');
  }

  /**
   * Get the entity field manager service.
   *
   * @return \Drupal\Core\Entity\EntityFieldManagerInterface
   *   The entity field manager.
   */
  public function entityFieldManager() : EntityFieldManagerInterface {
    return \Drupal::service('entity_field.manager');
  }

  /**
   * Get the database connection service.
   *
   * @return \Drupal\Core\Database\Connection
   *   The database connection service..
   */
  public function connection() : Connection {
    return \Drupal::service('database');
  }

  /**
   * Mockable wrapper around \Drupal::entityQuery(...).
   *
   * @param string $type
   *   A type such as node.
   *
   * @return \Drupal\Core\Entity\Query\QueryInterface
   *   The query object that can query the given entity type.
   */
  public function drupalEntityQuery(string $type) {
    return \Drupal::entityQuery($type);
  }

}

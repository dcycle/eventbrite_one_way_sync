<?php

namespace Drupal\eventbrite_one_way_sync_node\Utilities;

use Drupal\eventbrite_one_way_sync\Utilities\DependencyInjection as BaseDependencyInjection;
use Drupal\eventbrite_one_way_sync_node\Config\ConfigInterface;
use Drupal\eventbrite_one_way_sync_node\EventbriteOneWaySyncNodeInterface;
use Drupal\eventbrite_one_way_sync_node\EventNode\NodeFactoryInterface;
use Drupal\eventbrite_one_way_sync_node\FieldMapper\FieldMapperInterface;

/**
 * I like using a trait rather than services arguments which I find messy.
 */
trait DependencyInjection {

  use BaseDependencyInjection;

  /**
   * Get the node factory service.
   *
   * @return \Drupal\eventbrite_one_way_sync_node\EventNode\NodeFactoryInterface
   *   The node factory.
   */
  public function nodeFactory() : NodeFactoryInterface {
    return \Drupal::service('eventbrite_one_way_sync_node.node_factory');
  }

  /**
   * Get this module's config class.
   *
   * @return \Drupal\eventbrite_one_way_sync_node\Config\ConfigInterface
   *   The module's config class.
   */
  public function nodeConfig() : ConfigInterface {
    return \Drupal::service('eventbrite_one_way_sync_node.config');
  }

  /**
   * Get a field-mapping service.
   *
   * @return \Drupal\eventbrite_one_way_sync_node\FieldMapper\FieldMapperInterface
   *   The field-mapping service.
   */
  public function fieldMapper() : FieldMapperInterface {
    return \Drupal::service('eventbrite_one_way_sync_node.field_mapper');
  }

  /**
   * Get the module service.
   *
   * @return \Drupal\eventbrite_one_way_sync_node\EventbriteOneWaySyncNodeInterface
   *   The module service.
   */
  public function eventbriteOneWaySyncNode() : EventbriteOneWaySyncNodeInterface {
    return \Drupal::service('eventbrite_one_way_sync_node');
  }

}

<?php

namespace Drupal\eventbrite_one_way_sync_node\FieldMapper;

use Drupal\Core\Entity\EntityInterface;

/**
 * Maps field on node save.
 */
interface FieldMapperInterface {

  /**
   * Run the entity_presave hook.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   An entity.
   */
  public function hookEntityPresave(EntityInterface $entity);

}

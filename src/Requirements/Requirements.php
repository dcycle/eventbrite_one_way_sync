<?php

namespace Drupal\eventbrite_one_way_sync\Requirements;

use Drupal\eventbrite_one_way_sync\Utilities\DependencyInjection;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Check requirements and provide status for this module.
 */
class Requirements {

  use StringTranslationTrait;
  use DependencyInjection;

  /**
   * Testable implementation of hook_requirements().
   */
  public function hookRequirements(string $phase) : array {
    $requirements = [];
    if ($phase == 'runtime') {
    }
    return $requirements;
  }

}

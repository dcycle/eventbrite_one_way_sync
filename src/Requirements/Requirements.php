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
      $requirements += $this->pluginsRequirement();
    }
    return $requirements;
  }

  /**
   * Get requirements for plugins.
   *
   * @return array
   *   An array suitable for consuption by hook_requirements().
   */
  public function pluginsRequirement() : array {
    $requirements['eventbrite_one_way_sync_plugins'] = [
      'title' => $this->t('Number of eventbrite_one_way_sync plugins'),
      'description' => 'In order to be useful, eventbrite_one_way_sync requires at least one plugin in order to process events. In the absence of plugins, you might want to consider uninstalling this module.',
      'value' => count($this->plugins()),
      'severity' => count($this->plugins()) ? REQUIREMENT_INFO : REQUIREMENT_WARNING,
    ];

    return $requirements;
  }

}

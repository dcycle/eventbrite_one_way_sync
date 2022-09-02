<?php

namespace Drupal\eventbrite_one_way_sync\WebhookManager;

/**
 * Process a webhook.
 */
interface WebhookProcessorInterface {

  /**
   * Validate this webhook.
   *
   * @return bool
   *   TRUE if valid, FALSE otherwise.
   */
  public function validate() : bool;

  /**
   * Process this webhook.
   */
  public function process();

}

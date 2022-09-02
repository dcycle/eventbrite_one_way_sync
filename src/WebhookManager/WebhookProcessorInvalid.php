<?php

namespace Drupal\eventbrite_one_way_sync\WebhookManager;

/**
 * Process an invalid webhook.
 */
class WebhookProcessorInvalid implements WebhookProcessorInterface {

  /**
   * {@inheritdoc}
   */
  public function validate() : bool {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function process() {
    // Do nothing.
  }

}

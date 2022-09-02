<?php

namespace Drupal\eventbrite_one_way_sync\WebhookManager;

/**
 * Process a test webhook.
 */
class PayloadTestProcessor extends WebhookProcessorValid {

  /**
   * {@inheritdoc}
   */
  public function process() {
    $this->log->debug('Processing test payload; nothing to do.');
  }

}

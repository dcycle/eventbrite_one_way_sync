<?php

namespace Drupal\eventbrite_one_way_sync\WebhookManager;

use Drupal\webhook_receiver\WebhookReceiverLog\WebhookReceiverLogInterface;
use Drupal\webhook_receiver\Payload\PayloadInterface;

/**
 * Manage webhooks.
 */
interface WebhookManagerInterface {

  /**
   * Validate the payload array.
   *
   * @param \Drupal\webhook_receiver\Payload\PayloadInterface $payload
   *   The payload exactly as it was provided from the requestor.
   * @param \Drupal\webhook_receiver\WebhookReceiverLog\WebhookReceiverLogInterface $log
   *   A client-facing log.
   *
   * @return bool
   *   TRUE if valid, FALSE otherwise.
   */
  public function validatePayload(PayloadInterface $payload, WebhookReceiverLogInterface $log) : bool;

  /**
   * Process the payload array, assuming it has been validated.
   *
   * @param \Drupal\webhook_receiver\Payload\PayloadInterface $payload
   *   The payload exactly as it was provided from the requestor.
   * @param \Drupal\webhook_receiver\WebhookReceiverLog\WebhookReceiverLogInterface $log
   *   A client-facing log.
   * @param bool $simulate
   *   Whether or nt to simulate to action.
   */
  public function processPayload(PayloadInterface $payload, WebhookReceiverLogInterface $log, bool $simulate);

}

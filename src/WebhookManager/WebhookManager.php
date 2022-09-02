<?php

namespace Drupal\eventbrite_one_way_sync\WebhookManager;

use Drupal\webhook_receiver\WebhookReceiverLog\WebhookReceiverLogInterface;
use Drupal\webhook_receiver\Payload\PayloadInterface;

use Drupal\eventbrite_one_way_sync\Utilities\CommonUtilities;

/**
 * Manage webhooks.
 */
class WebhookManager implements WebhookManagerInterface {

  use CommonUtilities;

  /**
   * Get a processor based on a payload.
   *
   * @param \Drupal\webhook_receiver\Payload\PayloadInterface $payload
   *   A payload.
   * @param \Drupal\webhook_receiver\WebhookReceiverLog\WebhookReceiverLogInterface $log
   *   A logger.
   *
   * @return \Drupal\eventbrite_one_way_sync\WebhookManager\WebhookProcessorInterface
   *   A processor.
   */
  public function processor(PayloadInterface $payload, WebhookReceiverLogInterface $log) : WebhookProcessorInterface {
    $action = $this->payloadAction($payload);

    switch ($action) {
      case 'test':
        $log->debug('Processing test action.');
        return new PayloadTestProcessor($payload, $log);

      case 'event.updated':
        $log->debug('Processing event updated action.');
        return new PayloadUpdateProcessor($payload, $log);
    }

    $log->err('Unknown action ' . $action);
    return new WebhookProcessorInvalid();
  }

  /**
   * Given a payload, return its action.
   *
   * @param \Drupal\webhook_receiver\Payload\PayloadInterface $payload
   *   A payload.
   *
   * @return string
   *   A payload action string.
   */
  public function payloadAction(PayloadInterface $payload) : string {
    $payload_array = $payload->toArray();

    $candidate = $payload_array['config']['action'];

    $this->assertNonEmptyString($candidate, 'Payload action must be a non-empty string.');

    return $candidate;
  }

  /**
   * {@inheritdoc}
   */
  public function validatePayload(PayloadInterface $payload, WebhookReceiverLogInterface $log) : bool {
    return $this->processor($payload, $log)->validate();
  }

  /**
   * {@inheritdoc}
   */
  public function processPayload(PayloadInterface $payload, WebhookReceiverLogInterface $log, bool $simulate) {
    return $this->processor($payload, $log)->process();
  }

}

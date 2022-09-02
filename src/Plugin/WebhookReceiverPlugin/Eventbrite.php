<?php

namespace Drupal\eventbrite_one_way_sync\Plugin\WebhookReceiverPlugin;

use Drupal\webhook_receiver\WebhookReceiverPluginBase;
use Drupal\webhook_receiver\WebhookReceiverLog\WebhookReceiverLogInterface;
use Drupal\webhook_receiver\Payload\PayloadInterface;

use Drupal\eventbrite_one_way_sync\Utilities\DependencyInjection;

/**
 * Manages Eventbrite webhooks.
 *
 * @WebhookReceiverPluginAnnotation(
 *   id = "eventbrite_one_way_sync",
 *   description = @Translation("Manages Eventbrite webhooks."),
 *   weight = 0,
 *   examples = {
 *   },
 * )
 */
class Eventbrite extends WebhookReceiverPluginBase {

  use DependencyInjection;

  /**
   * {@inheritdoc}
   */
  public function validatePayload(PayloadInterface $payload, WebhookReceiverLogInterface $log) : bool {
    return $this->webhookManager()->validatePayload($payload, $log);
  }

  /**
   * {@inheritdoc}
   */
  public function processPayload(PayloadInterface $payload, WebhookReceiverLogInterface $log, bool $simulate) {
    $this->webhookManager()->processPayload($payload, $log, $simulate);
  }

}

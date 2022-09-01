<?php

namespace Drupal\eventbrite_one_way_sync\Plugin\WebhookReceiverPlugin;

use Drupal\webhook_receiver\WebhookReceiverPluginBase;
use Drupal\webhook_receiver\WebhookReceiverLog\WebhookReceiverLogInterface;
use Drupal\webhook_receiver\Payload\PayloadInterface;

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

  /**
   * {@inheritdoc}
   */
  public function validatePayload(PayloadInterface $payload, WebhookReceiverLogInterface2 $log) : bool {
    $log->debug('The payload is valid.');
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function processPayload(PayloadInterface $payload, WebhookReceiverLogInterface $log, bool $simulate) {
    $log->debug('The payload has been managed successfully');
  }

}

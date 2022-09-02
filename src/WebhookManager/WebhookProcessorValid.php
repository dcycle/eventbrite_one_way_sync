<?php

namespace Drupal\eventbrite_one_way_sync\WebhookManager;

use Drupal\webhook_receiver\WebhookReceiverLog\WebhookReceiverLogInterface;
use Drupal\webhook_receiver\Payload\PayloadInterface;
use Drupal\eventbrite_one_way_sync\Utilities\DependencyInjection;
use Drupal\eventbrite_one_way_sync\Utilities\CommonUtilities;

/**
 * Process a valid webhook.
 */
abstract class WebhookProcessorValid implements WebhookProcessorInterface {

  use CommonUtilities;
  use DependencyInjection;

  /**
   * The payload.
   *
   * @var \Drupal\webhook_receiver\Payload\PayloadInterface
   */
  protected $payload;


  /**
   * The log.
   *
   * @var \Drupal\webhook_receiver\WebhookReceiverLog\WebhookReceiverLogInterface
   */
  protected $log;

  /**
   * Constructor.
   *
   * @param \Drupal\webhook_receiver\Payload\PayloadInterface $payload
   *   The payload.
   * @param \Drupal\webhook_receiver\WebhookReceiverLog\WebhookReceiverLogInterface $log
   *   The log.
   */
  public function __construct(PayloadInterface $payload, WebhookReceiverLogInterface $log) {
    $this->payload = $payload;
    $this->log = $log;
  }

  /**
   * {@inheritdoc}
   */
  public function validate() : bool {
    // Make sure trying to get the info we need does not throw any
    // exceptions.
    $this->eventbriteAccountName();
    $this->eventId();

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function process() {
    $log = $this->log;
    $log->debug('Processing item.');
    $this->app()->session($this->eventbriteAccountName())
      ->importEventToQueue($this->eventId(), function ($x) use ($log) {
        $log->debug($x);
      });
  }

  /**
   * Get the Eventbrite account name.
   *
   * @return string
   *   The eventbrite account name, for example default.
   */
  public function eventbriteAccountName() : string {
    $payload = $this->payloadConfig();

    if (!array_key_exists('endpoint_url', $payload)) {
      throw new \Exception('the key endpoint_url does not exist in the payload');
    }

    if (!is_string($payload['endpoint_url'])) {
      throw new \Exception('the key endpoint_url is not a string');
    }

    if (!$payload['endpoint_url']) {
      throw new \Exception('the key endpoint_url is empty');
    }

    $matches = [];

    preg_match('/eventbrite_account_label=(.*)$/', $payload['endpoint_url'], $matches);

    if (count($matches) != 2) {
      throw new \Exception('Cannot find account name in ' . $payload['endpoint_url'] . ' because there are ' . count($matches) . ' matches, not 2');
    }

    return $matches[1];
  }

  /**
   * Get the payload as an array.
   *
   * @return array
   *   The payload as an array.
   */
  public function payload() : array {
    return $this->payload->toArray();
  }

  /**
   * Get the payload config as an array.
   *
   * @return array
   *   The payload config as an array.
   */
  public function payloadConfig() : array {
    $payload = $this->payload();

    if (!array_key_exists('config', $payload)) {
      throw new \Exception('payload config does not exist');
    }

    return $payload['config'];
  }

  /**
   * Get the Eventbrite ID to process.
   *
   * @return string
   *   An event ID, for example "123".
   */
  public function eventId() : string {
    $payload = $this->payload();

    if (!array_key_exists('api_url', $payload)) {
      throw new \Exception('the key api_url does not exist in the payload');
    }

    if (!is_string($payload['api_url'])) {
      throw new \Exception('the key api_url is not a string');
    }

    if (!$payload['api_url']) {
      throw new \Exception('the key api_url is empty');
    }

    $matches = [];

    preg_match('/^.*\/([0-9]*)\/$/', $payload['api_url'], $matches);

    if (count($matches) != 2) {
      throw new \Exception('Cannot find event in ' . $payload['api_url'] . ' because there are ' . count($matches) . ' matches, not 2');
    }

    return $matches[1];
  }

}

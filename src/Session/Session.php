<?php

namespace Drupal\eventbrite_one_way_sync\Session;

use Drupal\eventbrite_one_way_sync\Utilities\CommonUtilities;
use Drupal\eventbrite_one_way_sync\Utilities\DependencyInjection;
use GuzzleHttp\Client;
use Drupal\Component\Serialization\Json;

/**
 * A session is associated with a single key.
 */
class Session implements SessionInterface {

  use CommonUtilities;
  use DependencyInjection;

  const BASE_URL = 'https://www.eventbriteapi.com/v3';

  /**
   * The Eventbrite account label.
   *
   * @var string
   */
  protected $eventbriteAccountLabel;

  /**
   * Constructor.
   *
   * @param string $eventbrite_account_label
   *   The Eventbrite account label.
   */
  public function __construct(string $eventbrite_account_label) {
    $this->assertNonEmptyString($eventbrite_account_label, 'Eventbrite account label cannot be empty, it should be something like "default".');
    $this->eventbriteAccountLabel = $eventbrite_account_label;
  }

  /**
   * Get the Eventbrite organization ID associated with this session.
   *
   * @return string
   *   An Eventbrite organization ID.
   */
  public function organizationId() : string {
    return $this->config()->getOrganizationId($this->eventbriteAccountLabel);
  }

  /**
   * {@inheritdoc}
   */
  public function smokeTest() {
    $result = $this->get('/users/me/');

    foreach (['id', 'name'] as $eventbrite_account_label) {
      if (!array_key_exists($eventbrite_account_label, $result)) {
        throw new \Exception('The key ' . $eventbrite_account_label . ' does not exist in the response of the request, something might be wrong.');
      }
    }

    $result = $this->get('/organizations/' . $this->organizationId() . '/members/');

    if (!array_key_exists('members', $result)) {
      throw new \Exception('The key members does not exist in the response of the request for the organization members, something might be wrong.');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function apiKey() : string {
    $candidate = $this->config()->getPrivateToken($this->eventbriteAccountLabel);
    $this->assertNonEmptyString($candidate, 'API key cannot be empty.');
    return $candidate;
  }

  /**
   * Get all event occurrences from all pages for this session.
   *
   * This can take an extremely long time so it is best to run this on the
   * command line where the request will not time out.
   *
   * @param callable $log
   *   A log function.
   * @param callable $callback
   *   A callback for each occurrence.
   * @param int $max
   *   A maximum number of occurrences to obtain.
   */
  public function eventOccurrences(callable $log, callable $callback, int $max = PHP_INT_MAX) {
    $this->getAllPages('/organizations/' . $this->organizationId() . '/events/', $log, $callback, 'events', $max);
  }

  /**
   * Get all pages for a request from Eventbrite.
   *
   * This can take an extremely long time so it is best to run this on the
   * command line where the request will not time out.
   *
   * @param string $path
   *   A path such as /organizations/123/events/.
   * @param callable $log
   *   A log function.
   * @param callable $callback
   *   A callback for each occurrence.
   * @param string $eventbrite_account_label
   *   A key we are expecting in the return, such as 'events', or 'series'...
   * @param int $max
   *   A maximum number of occurrences to obtain.
   * @param int $page
   *   The page at which to start.
   */
  public function getAllPages(string $path, callable $log, callable $callback, string $eventbrite_account_label, int $max, int $page = 1) {
    if ($max <= 0) {
      return;
    }

    $log('Calling page ' . $page);

    $result = $this->get($path, [
      'page' => $page,
    ]);

    $more = $result['pagination']['has_more_items'];
    $page_size = $result['pagination']['page_size'];

    $log('Total items: ' . $result['pagination']['object_count']);
    $log('Current page number: ' . $result['pagination']['page_number']);
    $log('Page size is: ' . $page_size);
    $log('Page count is: ' . $result['pagination']['page_count']);
    $log('Has more items: ' . ($more ? 'true' : 'false'));

    foreach ($result[$eventbrite_account_label] as $line) {
      if ($max <= 0) {
        return;
      }
      $callback($line);
      $max--;
    }

    if ($more) {
      $this->getAllPages($path, $log, $callback, $eventbrite_account_label, $max, $page + 1);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function importExistingToQueue(int $max = PHP_INT_MAX) {
    $log = function (string $message) {
      print_r('Log: ' . $message . PHP_EOL);
    };

    $this->eventOccurrences($log, function ($struct) use ($log) {
      $this->processorFactory()->getProcessor($this->eventbriteAccountLabel, $struct, $log)->process();
    }, $max);
  }

  /**
   * Get a query result from the Eventbrite API.
   *
   * @param string $path
   *   A path such as /organizations/123/events/.
   * @param array $query_params
   *   Query parameters to send to Eventbrite, other than the API key which
   *   is added automatically.
   */
  public function get(string $path, array $query_params = []) {
    $this->assertNonEmptyString($path, 'Path cannot be empty.');
    $full_url = self::BASE_URL . $path;

    $client = new Client();

    $response = $client->request('GET', $full_url, [
      'query' => $query_params + [
        'token' => $this->apiKey(),
      ],
    ]);
    return Json::decode($response->getBody());
  }

}

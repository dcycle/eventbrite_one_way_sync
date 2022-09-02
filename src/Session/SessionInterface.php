<?php

namespace Drupal\eventbrite_one_way_sync\Session;

/**
 * A session is associated with a single Eventbrite account key.
 */
interface SessionInterface {

  /**
   * Get the API key associated with this session.
   *
   * @return string
   *   An API key.
   */
  public function apiKey() : string;

  /**
   * Import all existing events to the queue.
   *
   * This can take an extremely long time so it is best to run this on the
   * command line where the request will not time out.
   *
   * @param int $max
   *   A maximum number of occurrences to obtain.
   */
  public function importExistingToQueue(int $max = PHP_INT_MAX);

  /**
   * Import event to queue.
   *
   * @param string $event_id
   *   An event id.
   * @param callable $log
   *   A log function.
   */
  public function importEventToQueue(string $event_id, callable $log);

  /**
   * Run a smoke test on this session, ensuring we have access.
   */
  public function smokeTest();

}

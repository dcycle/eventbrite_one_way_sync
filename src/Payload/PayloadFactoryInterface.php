<?php

namespace Drupal\eventbrite_one_way_sync\Payload;

/**
 * The payload factory is used to generate payload objects.
 */
interface PayloadFactoryInterface {

  /**
   * Given a string, return a Payload object.
   *
   * @param string $payload_string
   *   A payload string.
   *
   * @return \Drupal\eventbrite_one_way_sync\Payload\PayloadInterface
   *   A payload object.
   */
  public function fromString(string $payload_string) : PayloadInterface;

}

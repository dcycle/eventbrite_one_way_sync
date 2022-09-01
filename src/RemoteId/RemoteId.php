<?php

namespace Drupal\eventbrite_one_way_sync\RemoteId;

use Drupal\eventbrite_one_way_sync\Utilities\CommonUtilities;

/**
 * Represents and allows manipulation of remote IDs.
 *
 * Remote IDs are multi-part ID representing a record on Eventbrite and
 * contain information about the Eventbrite account, the record type (series,
 * event), and resource ID.
 */
class RemoteId implements RemoteIdInterface {

  use CommonUtilities;

  /**
   * The remote ID as a string.
   *
   * @var string
   */
  protected $raw;

  /**
   * Constructor.
   *
   * @param string $raw
   *   The remote ID as a string.
   */
  public function __construct(string $raw) {
    $this->raw = $raw;
  }

  /**
   * {@inheritdoc}
   */
  public function recordType() : string {
    $candidate = $this->getPart(1, 'Record ID cannot be empty');

    if (!in_array($candidate, ['event', 'series'])) {
      throw new \Exception($candidate . ' is not one of event, series.');
    }

    return $candidate;
  }

  /**
   * {@inheritdoc}
   */
  public function toString() : string {
    return $this->raw;
  }

  /**
   * {@inheritdoc}
   */
  public function recordId() : string {
    return $this->getPart(2, 'Record ID cannot be empty');
  }

  /**
   * {@inheritdoc}
   */
  public function eventbriteAccountName() : string {
    return $this->getPart(0, 'Account name cannot be empty');
  }

  /**
   * Get one of three (3) parts of the raw remote ID.
   *
   * @param int $part
   *   A part, 0, 1, or 2.
   * @param string $err_message
   *   The error if this is an empty string.
   *
   * @return string
   *   A string.
   */
  public function getPart(int $part, string $err_message) : string {
    $parts = explode(':', $this->raw);

    if (count($parts) != 3) {
      throw new \Exception('The remote ID is invalid, it should contain exactly two colons (:).');
    }

    $candidate = $parts[$part];

    $this->assertNonEmptyString($candidate, $err_message);

    return $candidate;
  }

}

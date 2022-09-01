<?php

namespace Drupal\eventbrite_one_way_sync\RemoteId;

/**
 * Represents and allows manipulation of remote IDs.
 *
 * Remote IDs are multi-part ID representing a record on Eventbrite and
 * contain information about the Eventbrite account, the record type (series,
 * event), and resource ID.
 */
interface RemoteIdInterface {

  /**
   * Get the remote ID as a string.
   *
   * @return string
   *   The remote ID as a string.
   */
  public function toString() : string;

  /**
   * Get the record type (event or series).
   *
   * @return string
   *   The Eventbrite account name.
   */
  public function recordType() : string;

  /**
   * Get the record id.
   *
   * @return string
   *   The record ID.
   */
  public function recordId() : string;

  /**
   * Get the Eventbrite account name.
   *
   * @return string
   *   The Eventbrite account name.
   */
  public function eventbriteAccountName() : string;

}

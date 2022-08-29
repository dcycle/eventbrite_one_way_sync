<?php

namespace Drupal\eventbrite_one_way_sync\Processor;

use Drupal\eventbrite_one_way_sync\Utilities\CommonUtilities;
use Drupal\eventbrite_one_way_sync\Utilities\DependencyInjection;

/**
 * Processor for an event struct coming from Eventbrite.
 */
abstract class ProcessorBase implements ProcessorInterface {

  use CommonUtilities;
  use DependencyInjection;

  /**
   * The Eventbrite account label, such as default.
   *
   * @var string
   */
  protected $eventbriteAccountLabel;

  /**
   * The struct from Eventbrite.
   *
   * @var array
   */
  protected $struct;

  /**
   * The log function.
   *
   * @var callable
   */
  protected $log;

  /**
   * Constructor.
   *
   * @param string $eventbrite_account_label
   *   The Eventbrite account label, such as default.
   * @param array $struct
   *   The Eventbite struct.
   * @param callable $log
   *   The log function.
   */
  public function __construct(string $eventbrite_account_label, array $struct, callable $log) {
    $this->assertNonEmptyString($eventbrite_account_label, 'Key cannot be empty');

    $this->eventbriteAccountLabel = $eventbrite_account_label;
    $this->struct = $struct;
    $this->log = $log;
  }

  /**
   * Get the event ID of this item, which is in fact an occurrence ID.
   *
   * @return string
   *   The event ID of this item, which is in fact an occurrence ID.
   */
  public function eventId() : string {
    return $this->struct['id'];
  }

  /**
   * Log a message to the log.
   *
   * @param string $message
   *   A message to log.
   */
  public function log(string $message) {
    $callable = $this->log;
    $callable($message);
  }

  /**
   * Process this item.
   */
  public function process() {
    $remote_id = $this->remoteId();
    $occurrence_id = $this->occurrenceId();
    $struct = $this->struct;

    $this->database()->update(
      remote_id: $remote_id,
      occurrence_id: $occurrence_id,
      struct: $struct,
    );
  }

  /**
   * Get the Remote ID of this item.
   *
   * @return string
   *   The remote ID of this item, for example default:series:123 or
   *   default:event:123.
   */
  public function remoteId() : string {
    return $this->eventbriteAccountLabel . ':' . $this->recordTypeAndId();
  }

  /**
   * Get the occurrence ID.
   *
   * @return string
   *   The occurrence ID of this item, for example default:event:123.
   */
  public function occurrenceId() : string {
    return $this->eventbriteAccountLabel . ':event:' . $this->eventId();

  }

  /**
   * Get the record type and ID of this item, to create the Remote ID.
   *
   * @return string
   *   The record type and ID, for example series:123 or event:123.
   */
  abstract public function recordTypeAndId(): string;

}

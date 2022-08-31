<?php

namespace Drupal\eventbrite_one_way_sync\EventbriteEvent;

use Drupal\eventbrite_one_way_sync\Utilities\DependencyInjection;
use Drupal\eventbrite_one_way_sync\Utilities\CommonUtilities;

/**
 * Represents an Event on Eventbrite.
 */
class EventbriteEvent implements EventbriteEventInterface {

  use DependencyInjection;
  use CommonUtilities;

  /**
   * The Eventbrite account label.
   *
   * @var string
   */
  protected $eventbriteAccountLabel;

  /**
   * The remote ID.
   *
   * @var string
   */
  protected $remoteId;

  /**
   * Constructor.
   *
   * @param string $eventbriteAccountLabel
   *   The Eventbrite account label.
   * @param string $remoteId
   *   The remote ID.
   */
  public function __construct(string $eventbriteAccountLabel, string $remoteId) {
    $this->assertNonEmptyString($eventbriteAccountLabel, 'Account label cannot be empty.');
    $this->assertNonEmptyString($remoteId, 'Remote ID cannot be empty.');

    $this->eventbriteAccountLabel = $eventbriteAccountLabel;
    $this->remoteId = $remoteId;
  }

  /**
   * {@inheritdoc}
   */
  public function removeFromQueue() {
    $this->database()->remove($this->remoteId());
  }

  /**
   * Get a remote ID such as default:event:123 or default:series:123.
   *
   * @return string
   *   A remote ID such as default:event:123 or default:series:123.
   */
  public function remoteId() : string {
    return $this->remoteId;
  }

  /**
   * Get the Eventbrite title.
   *
   * @return string
   *   The Eventbrite title.
   */
  public function getTitle() : string {
    return 'THIS SHOULD BE MODIFIED';
  }

  /**
   * Get the Eventbrite account label.
   *
   * @return string
   *   The Eventbrite account label such as default.
   */
  public function eventbriteAccountLabel() : string {
    return $this->eventbriteAccountLabel;
  }

}

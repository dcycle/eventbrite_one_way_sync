<?php

namespace Drupal\eventbrite_one_way_sync\EventbriteEvent;

use Drupal\eventbrite_one_way_sync\RemoteId\RemoteId;
use Drupal\eventbrite_one_way_sync\RemoteId\RemoteIdInterface;
use Drupal\eventbrite_one_way_sync\Utilities\CommonUtilities;
use Drupal\eventbrite_one_way_sync\Utilities\DependencyInjection;

/**
 * Get eventbrite events.
 */
class EventbriteEventFactory implements EventbriteEventFactoryInterface {

  use CommonUtilities;
  use DependencyInjection;

  /**
   * {@inheritdoc}
   */
  public function fromRemoteId(string $remote_id) : EventbriteEventInterface {
    if (!$remote_id) {
      return new EventbriteEventDoesNotExist();
    }
    return $this->fromNonEmptyRemoteId($remote_id);
  }

  /**
   * Given a remote ID, return an Event.
   *
   * @param string $remote_id
   *   A remote id which can exist or not, but is non-empty.
   *
   * @return \Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventInterface
   *   An event.
   */
  public function fromNonEmptyRemoteId(string $remote_id) : EventbriteEventInterface {
    $this->assertNonEmptyString($remote_id, 'remote_id cannot be empty');

    $result = $this->database()->getRemoteId($remote_id);

    if (!count($result)) {
      return new EventbriteEventDoesNotExist();
    }

    return $this->fromExistingResult(new RemoteId($remote_id), $result);
  }

  /**
   * Given a queue result, return an Event.
   *
   * @param \Drupal\eventbrite_one_way_sync\RemoteId\RemoteIdInterface $remote_id
   *   A remote id which exists.
   * @param array $result
   *   A queue result which is an array of objects, each containing the keys
   *   * occurrence_id
   *   * struct.
   *
   * @return \Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventInterface
   *   An event.
   */
  public function fromExistingResult(RemoteIdInterface $remote_id, array $result) : EventbriteEventInterface {
    $this->assertNonEmptyArray($result, 'result cannot be empty');

    switch ($remote_id->recordType()) {
      case 'event':
        return new EventbriteEventSingleDate($remote_id, $result);

      case 'series':
        return new EventbriteEventMultiDate($remote_id, $result);

      default:
        return new EventbriteEventInvalid($remote_id);
    }
  }

}

<?php

namespace Drupal\eventbrite_one_way_sync\EventbriteEvent;

use Drupal\eventbrite_one_way_sync\RemoteId\RemoteIdInterface;
use Drupal\eventbrite_one_way_sync\Utilities\CommonUtilities;
use Drupal\eventbrite_one_way_sync\Utilities\DependencyInjection;

/**
 * An event in the queue.
 */
abstract class EventbriteEventInQueue implements EventbriteEventInterface, EventbriteEventInQueueInterface {

  use DependencyInjection;
  use CommonUtilities;

  /**
   * The remote ID.
   *
   * @var \Drupal\eventbrite_one_way_sync\RemoteId\RemoteIdInterface
   */
  protected $remoteId;

  /**
   * Constructor.
   *
   * @param \Drupal\eventbrite_one_way_sync\RemoteId\RemoteIdInterface $remoteId
   *   A remote ID.
   */
  public function __construct(RemoteIdInterface $remoteId) {
    $this->remoteId = $remoteId;
  }

  /**
   * {@inheritdoc}
   */
  public function removeFromQueue() {
    $this->database()->remove($this->remoteId->toString());
  }

  /**
   * {@inheritdoc}
   */
  public function remoteId() : RemoteIdInterface {
    return $this->remoteId;
  }

}

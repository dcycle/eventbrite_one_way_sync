<?php

namespace Drupal\eventbrite_one_way_sync\EventbriteEvent;

use Drupal\Component\Serialization\Json;
use Drupal\eventbrite_one_way_sync\RemoteId\RemoteIdInterface;

/**
 * Represents an Event on Eventbrite.
 */
class EventbriteEventValid extends EventbriteEventInQueue implements EventbriteEventValidInterface {

  /**
   * A queue result which is an array of objects.
   *
   * Each array element contains the keys
   * * occurrence_id
   * * struct.
   *
   * @var array
   */
  protected $result;

  /**
   * Constructor.
   *
   * @param \Drupal\eventbrite_one_way_sync\RemoteId\RemoteIdInterface $remoteId
   *   A remote ID.
   * @param array $result
   *   A queue result which is an array of objects, each containing the keys
   *   * occurrence_id
   *   * struct.
   */
  public function __construct(RemoteIdInterface $remoteId, array $result) {
    $this->result = $result;
    parent::__construct($remoteId);
  }

  /**
   * Get the title of this event.
   *
   * @return string
   *   The title of this event.
   */
  public function getTitle() : string {
    return $this->struct()['name']['text'];
  }

  /**
   * JSON-decode a struct.
   *
   * @param int $id
   *   The position of the object in the result.
   *
   * @return array
   *   The decoded result.
   */
  public function struct(int $id = 0) : array {
    return JSON::decode($this->result[$id]->struct);
  }

  /**
   * {@inheritdoc}
   */
  public function process() : bool {
    $this->plugins()->process($this);
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function toStruct(array &$struct) : bool {
    $original = $struct;
    $ret = [];
    foreach ($this->result as $line) {
      $ret[$line->occurrence_id] = $line->struct;
    }
    // If there are duplicate keys, $ret's keys take precedence.
    $struct = $ret + $struct;
    return ($struct != $original);
  }

}

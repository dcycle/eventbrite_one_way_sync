<?php

namespace Drupal\eventbrite_one_way_sync\Payload;

use Drupal\eventbrite_one_way_sync\ArrayPathfinder\ArrayPathfinderInterface;

/**
 * The payload factory is used to generate payload objects.
 */
class PayloadFactory implements PayloadFactoryInterface {

  /**
   * The injected pathfinder service.
   *
   * @var \Drupal\eventbrite_one_way_sync\ArrayPathfinder\ArrayPathfinderInterface
   */
  protected $arrayPathfinder;

  /**
   * Constructor.
   *
   * @param \Drupal\eventbrite_one_way_sync\ArrayPathfinder\ArrayPathfinderInterface $array_pathfinder
   *   The injected array pathfinder service.
   */
  public function __construct(ArrayPathfinderInterface $array_pathfinder) {
    $this->arrayPathfinder = $array_pathfinder;
  }

  /**
   * {@inheritdoc}
   */
  public function fromString(string $payload_string) : PayloadInterface {
    return new Payload($payload_string, $this->arrayPathfinder);
  }

}

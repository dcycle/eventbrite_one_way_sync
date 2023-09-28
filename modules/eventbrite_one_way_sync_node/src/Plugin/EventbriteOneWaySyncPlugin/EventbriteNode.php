<?php

namespace Drupal\eventbrite_one_way_sync_node\Plugin\EventbriteOneWaySyncPlugin;

use Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventValidInterface;
use Drupal\eventbrite_one_way_sync\EventbriteOneWaySyncPluginBase;
use Drupal\eventbrite_one_way_sync\SelfTest\SelfTestLogTrait;
use Drupal\eventbrite_one_way_sync_node\Utilities\DependencyInjection;

/**
 * Sync Eventbrite events to nodes.
 *
 * @EventbriteOneWaySyncPluginAnnotation(
 *   id = "eventbrite_one_way_sync_node",
 *   description = @Translation("Sync Eventbrite events to nodes."),
 *   weight = 0,
 *   examples = {
 *   },
 * )
 */
class EventbriteNode extends EventbriteOneWaySyncPluginBase {

  use DependencyInjection;
  use SelfTestLogTrait;

  /**
   * {@inheritdoc}
   */
  public function process(EventbriteEventValidInterface $event) {
    return $this->eventbriteOneWaySyncNode()->process($event);
  }

  /**
   * {@inheritdoc}
   */
  public function smokeTest(string $eventbrite_account_label) {
    $this->log('Making sure all the configuration is set properly for account ' . $eventbrite_account_label . '.');
    $this->nodeConfig()->checkNodeTypeAndFields($eventbrite_account_label);
    $this->log('All good.');
  }

}

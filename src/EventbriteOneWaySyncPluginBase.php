<?php

namespace Drupal\eventbrite_one_way_sync;

use Drupal\eventbrite_one_way_sync\EventbriteEvent\EventbriteEventValidInterface;
use Drupal\Component\Plugin\PluginBase;

/**
 * A base class to help developers implement EventbriteOneWaySyncPlugin objects.
 *
 * @see \Drupal\eventbrite_one_way_sync\Annotation\EventbriteOneWaySyncPluginAnnotation
 * @see \Drupal\eventbrite_one_way_sync\EventbriteOneWaySyncPluginInterface
 */
abstract class EventbriteOneWaySyncPluginBase extends PluginBase implements EventbriteOneWaySyncPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function process(EventbriteEventValidInterface $event) {
    // Do nothing.
  }

  /**
   * {@inheritdoc}
   */
  public function smokeTest(string $eventbrite_account_label) {
    // Do nothing.
  }

}

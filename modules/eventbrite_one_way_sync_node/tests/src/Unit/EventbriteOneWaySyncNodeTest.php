<?php

namespace Drupal\Tests\eventbrite_one_way_sync_node\Unit;

use Drupal\eventbrite_one_way_sync_node\EventbriteOneWaySyncNode;
use PHPUnit\Framework\TestCase;

/**
 * Test EventbriteOneWaySyncNode.
 *
 * @group eventbrite_one_way_sync
 */
class EventbriteOneWaySyncNodeTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(EventbriteOneWaySyncNode::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}

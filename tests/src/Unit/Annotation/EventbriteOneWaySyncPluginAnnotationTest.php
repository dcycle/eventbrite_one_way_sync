<?php

namespace Drupal\Tests\eventbrite_one_way_sync\Unit\Annotation;

use Drupal\eventbrite_one_way_sync\Annotation\EventbriteOneWaySyncPluginAnnotation;
use PHPUnit\Framework\TestCase;

/**
 * Test EventbriteOneWaySyncPluginAnnotation.
 *
 * @group eventbrite_one_way_sync
 */
class EventbriteOneWaySyncPluginAnnotationTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(EventbriteOneWaySyncPluginAnnotation::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}

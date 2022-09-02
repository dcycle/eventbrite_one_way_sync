<?php

namespace Drupal\Tests\eventbrite_one_way_sync\Unit\WebhookManager;

use Drupal\eventbrite_one_way_sync\WebhookManager\WebhookProcessorValid;
use PHPUnit\Framework\TestCase;

/**
 * Test WebhookProcessorValid.
 *
 * @group eventbrite_one_way_sync
 */
class WebhookProcessorValidTest extends TestCase {

  /**
   * Test for eventId().
   *
   * @param string $message
   *   The test message.
   * @param string $input
   *   The input.
   * @param string $expected
   *   The expected result.
   *
   * @cover ::eventId
   * @dataProvider providerEventId
   */
  public function testEventId(string $message, string $input, string $expected) {
    $object = $this->getMockBuilder(WebhookProcessorValid::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods([
        'payload',
      ])
      ->disableOriginalConstructor()
      ->getMock();

    $object->method('payload')
      ->willReturn([
        'api_url' => $input,
      ]);

    $output = $object->eventId();

    if ($output != $expected) {
      print_r([
        'message' => $output,
        'output' => $output,
        'expected' => $expected,
      ]);
    }

    $this->assertTrue($output == $expected, $message);
  }

  /**
   * Provider for testEventId().
   */
  public function providerEventId() {
    return [
      [
        'message' => 'happy path',
        'input' => 'https://www.example.com/v3/events/412431292097/',
        'expected' => '412431292097',
      ],
    ];
  }

  /**
   * Test for eventbriteAccountName().
   *
   * @param string $message
   *   The test message.
   * @param string $input
   *   The input.
   * @param string $expected
   *   The expected result.
   *
   * @cover ::eventbriteAccountName
   * @dataProvider providerEventbriteAccountName
   */
  public function testEventbriteAccountName(string $message, string $input, string $expected) {
    $object = $this->getMockBuilder(WebhookProcessorValid::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods([
        'payloadConfig',
      ])
      ->disableOriginalConstructor()
      ->getMock();

    $object->method('payloadConfig')
      ->willReturn([
        'endpoint_url' => $input,
      ]);

    $output = $object->eventbriteAccountName();

    if ($output != $expected) {
      print_r([
        'message' => $output,
        'output' => $output,
        'expected' => $expected,
      ]);
    }

    $this->assertTrue($output == $expected, $message);
  }

  /**
   * Provider for testEventbriteAccountName().
   */
  public function providerEventbriteAccountName() {
    return [
      [
        'message' => 'happy path',
        'input' => 'http://whatever?eventbrite_account_label=selftest',
        'expected' => 'selftest',
      ],
    ];
  }

}

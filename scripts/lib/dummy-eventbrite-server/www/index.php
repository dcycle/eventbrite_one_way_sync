<?php

// @codingStandardsIgnoreStart

/**
 * This file is a dummy Eventbrite server to demonstrate that this module
 * works as expected.
 */

const ORGANIZATION='MY_ORGANIZATION';
const TOKEN='MY_TOKEN';

try {
  check_token(get('token'));

  switch (get('url')) {
    case 'organizations/' . ORGANIZATION . '/members/':
      print(json_encode(organization_members()));
      break;

    case 'organizations/' . ORGANIZATION . '/events/':
      print(json_encode(organization_events()));
      break;

    case 'users/me/';
      print(json_encode(users_me()));
      break;

    default:
      throw new \Exception('Sorry, this URL is not valid.');
  }
}
catch (\Throwable $t) {
  print_r($t->getMessage() . PHP_EOL);
  print_r('(' . $t->getLine() . ')');
}

function check_token(string $token) {
  if ($token !== TOKEN) {
    throw new \Exception('Please use ?token=' . TOKEN);
  }
}

function get(string $param) : string {
  $ret = '';

  if (array_key_exists($param, $_GET)) {
    $ret = $_GET[$param];
  }

  return $ret;
}

function organization_members() : array {
  return [
    'members' => [
      'me' => [],
    ],
  ];
}

function organization_events() : array {
  return [
    'pagination' => [
      'has_more_items' => get('page') == 2 ? FALSE : TRUE,
      'page_size' => get('page') == 2 ? 1 : 3,
      'object_count' => 4,
      'page_count' => 2,
      'page_number' => get('page'),
    ],
    'events' => get('page') == 2 ? events_page_2() : events_page_1(),
  ];
}

function events_page_1() : array {
  return [
    [
      "name" => [
        "text" => "My event 1",
      ],
      "start" => [
        "timezone" => "America/Montreal",
        "local" => "2022-08-30T19:00:00",
        "utc" => "2022-08-30T23:00:00Z",
      ],
      "end" => [
        "timezone" => "America/Montreal",
        "local" => "2022-08-30T22:00:00",
        "utc" => "2022-08-31T02:00:00Z",
      ],
      "organization_id" => ORGANIZATION,
      "status" => "draft",
      "id" => "1",
      "is_series" => TRUE,
      "series_id" => "S1",
    ],
    [
      "name" => [
        "text" => "My event 2",
      ],
      "start" => [
        "timezone" => "America/Montreal",
        "local" => "2100-08-30T19:00:00",
        "utc" => "2100-08-30T23:00:00Z",
      ],
      "end" => [
        "timezone" => "America/Montreal",
        "local" => "2100-08-30T22:00:00",
        "utc" => "2100-08-31T02:00:00Z",
      ],
      "organization_id" => ORGANIZATION,
      "status" => "draft",
      "id" => "2",
      "is_series" => TRUE,
      "series_id" => "S1",
    ],
    [
      "name" => [
        "text" => "My event 3",
      ],
      "start" => [
        "timezone" => "America/Montreal",
        "local" => "2101-08-30T19:00:00",
        "utc" => "2101-08-30T23:00:00Z",
      ],
      "end" => [
        "timezone" => "America/Montreal",
        "local" => "2102-08-30T22:00:00",
        "utc" => "2102-08-31T02:00:00Z",
      ],
      "organization_id" => ORGANIZATION,
      "status" => "draft",
      "id" => "3",
      "is_series" => FALSE,
    ],
  ];
}

function events_page_2() : array {
  return [
    [
      "name" => [
        "text" => "My event 4",
      ],
      "start" => [
        "timezone" => "America/Montreal",
        "local" => "2022-08-30T19:00:00",
        "utc" => "2022-08-30T23:00:00Z",
      ],
      "end" => [
        "timezone" => "America/Montreal",
        "local" => "2022-08-30T22:00:00",
        "utc" => "2022-08-31T02:00:00Z",
      ],
      "organization_id" => ORGANIZATION,
      "status" => "draft",
      "id" => "1",
      "is_series" => TRUE,
      "series_id" => "S1",
    ]
  ];
}

function users_me() : array {
  return [
    "emails" => [
      [
        "email" => "dummy_account@example.com",
        "verified" => false,
        "primary" => true,
      ],
    ],
    "id" => "12345",
    "name" => "Dummy Account for Drupal Eventbrite One-Way Sync Module",
    "first_name" => "Dummy",
    "last_name" => "Account",
    "is_public" => false,
    "image_id" => "1234",
  ];
}
// @codingStandardsIgnoreEnd

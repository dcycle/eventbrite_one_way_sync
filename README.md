[![CircleCI](https://circleci.com/gh/dcycle/eventbrite_one_way_sync/tree/1.x.svg?style=svg)](https://circleci.com/gh/dcycle/eventbrite_one_way_sync/tree/1.x)

Eventbrite Event One-Way Sync
=====

Synchronize events from Eventbrite to Drupal nodes. This only synchronizes events, not attendees, orders, ticket_classes or venues.

No GUI
-----

This module does not come with a graphical user interface. You will need to interact with it through code as described below.

This module obtains Eventbrite events in two ways, and we will demonstrate, below, how each works.

Pull events from Eventbrite
-----

This module can connect to Eventbrite and synchronize events which pre-exist the installation of this module.

Have Eventbrite push events to Drupal when they change, via Webhooks
-----

Connecting via webhooks is optional.

This uses the [Webhook Receiver](https://www.drupal.org/project/eventbrite_one_way_sync) module.

This requires a running, publicly-accessible Drupal site running on standard port. (At the time of this writing, Eventbrite will fail if it tries to access Webhooks that look like example.com:1234 that use a nonstandard port number.)

Initial setup: make sure you have an Eventbrite account and event
-----

* Make sure you have an Eventbrite account.
* Make sure you have a running Drupal site with this module enabled.
* Create a node type, or use an existing node type (for example "event") with at least these fields:
  * A Text (plain) field (for example "field_eventbrite_id") to store the eventbrite event ID.
  * A Text (plain, long) field (for example "field_eventbrite_struct") to store the eventbrite struct.
  * A **multi-value** Date Range field (for example "field_eventbrite_date") to store the eventbrite dates.
* Create an event with more than one occurrence; and another event with only one occurrence (these are managed differently in Eventbrite, as we'll see later).
* Make sure you have a website URL
* Go to https://www.eventbrite.com/account-settings/apps and create an API key. Take note of the **private token**, it is the only information we'll use.
* You will need your organization number, which you can find by visiting the following URL.

    https://www.eventbriteapi.com/v3/users/me/organizations/?token=PUT_YOUR_PRIVATE_TOKEN_HERE

* Edit your site's ./sites/default/settings.php and add the following:

    $config['eventbrite_one_way_sync.unversioned']['api-keys'] = [
      'default' => [
        'private_token' => 'PUT_YOUR_PRIVATE_TOKEN_HERE',
        'organization_id' => '123456789',
      ],
    ];

* Tell Drupal what your node type and fields are:

    eventbrite_one_way_sync_node()->nodeConfig()->setNodeTypeAndFields('default', 'event', 'field_eventbrite_id', 'field_eventbrite_struct', 'field_eventbrite_date');

Smoke-test your configuration
-----

Smoke-test your configuration before moving to the next step:

    eventbrite_one_way_sync()->smokeTest()->run(key: 'default');

If you're not getting any errors, congratulations, you can move on!

Seeing your events programmatically
-----

The following instructions will not synchronize your events, but they give you an idea of how this module works. This gets a maximum of 100 events and prints them to the screen:

    eventbrite_one_way_sync()->session('default')
      ->eventOccurrences(function($x) {
          print_r($x . PHP_EOL);
        }, function($x) {
          print_r('Event ' . $x['id'] . '; ' . ($x['is_series'] ? 'part of series ' . $x['series_id'] : 'Single-time event') . PHP_EOL);
        }, max: 100);

If you have events with multiple occurrences, and events with a single occurrence, you'll see something like:

    Calling page 1
    Total items: 9
    Current page number: 1
    Page size is: 50
    Page count is: 1
    Has more items: false
    Event 408827041687; part of series 408827011597
    Event 408827051717; part of series 408827011597
    Event 408827061747; part of series 408827011597
    Event 408827071777; part of series 408827011597
    Event 408827081807; part of series 408827011597
    Event 408827091837; part of series 408827011597
    Event 408958615227; Single-time event
    Event 408827101867; part of series 408827011597
    Event 408827111897; part of series 408827011597

In this case, we have two events, event:408958615227, and series:408827011597. The way Eventbrite presents this information is rather odd in my opinion.

Three types of records
-----

The first record type we have is a single-time event, in our example 408958615227. It looks like this:

    eventbrite_one_way_sync()->session('default')->get('/events/408958615227');
    => [
         "name" => [
           "text" => "Single Date Event",
           "html" => "Single Date Event",
         ],
         "description" => [
           "text" => "test",
           "html" => "test",
         ],
         "url" => "https://www.eventbrite.com/e/single-date-event-tickets-408958615227",
         "start" => [
           "timezone" => "Europe/Amsterdam",
           "local" => "2022-10-05T19:00:00",
           "utc" => "2022-10-05T17:00:00Z",
         ],
         "end" => [
           "timezone" => "Europe/Amsterdam",
           "local" => "2022-10-05T22:00:00",
           "utc" => "2022-10-05T20:00:00Z",
         ],
         "organization_id" => "1114019437563",
         "created" => "2022-08-26T20:41:23Z",
         "changed" => "2022-08-26T20:41:34Z",
         "capacity" => 0,
         "capacity_is_custom" => false,
         "status" => "draft",
         "currency" => "USD",
         "listed" => true,
         "shareable" => false,
         "invite_only" => false,
         "online_event" => true,
         "show_remaining" => false,
         "tx_time_limit" => 1200,
         "hide_start_date" => false,
         "hide_end_date" => false,
         "locale" => "en_US",
         "is_locked" => false,
         "privacy_setting" => "unlocked",
         "is_series" => false,
         "is_series_parent" => false,
         "inventory_type" => "limited",
         "is_reserved_seating" => false,
         "show_pick_a_seat" => false,
         "show_seatmap_thumbnail" => false,
         "show_colors_in_seatmap_thumbnail" => false,
         "source" => "coyote",
         "is_free" => true,
         "version" => null,
         "summary" => "test",
         "facebook_event_id" => null,
         "logo_id" => null,
         "organizer_id" => "52394692353",
         "venue_id" => null,
         "category_id" => null,
         "subcategory_id" => null,
         "format_id" => "18",
         "id" => "408958615227",
         "resource_uri" => "https://www.eventbriteapi.com/v3/events/408958615227/",
         "is_externally_ticketed" => false,
         "logo" => null,
       ]

The second record type we have is a time in a series, in our example 408827041687. It looks like this:

    eventbrite_one_way_sync()->session('default')->get('/events/408827041687');
    => [
         "name" => [
           "text" => "Event to test integration with my API",
           "html" => "Event to test integration with my API",
         ],
         "description" => [
           "text" => "This is to test API integration",
           "html" => "This is to test API integration",
         ],
         "url" => "https://www.eventbrite.com/e/event-to-test-integration-with-my-api-tickets-408827041687",
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
         "organization_id" => "1114019437563",
         "created" => "2022-08-26T16:14:22Z",
         "changed" => "2022-08-26T19:17:22Z",
         "published" => "2022-08-26T16:15:30Z",
         "capacity" => 150,
         "capacity_is_custom" => false,
         "status" => "draft",
         "currency" => "USD",
         "listed" => true,
         "shareable" => true,
         "invite_only" => false,
         "online_event" => false,
         "show_remaining" => false,
         "tx_time_limit" => 1200,
         "hide_start_date" => false,
         "hide_end_date" => false,
         "locale" => "en_US",
         "is_locked" => false,
         "privacy_setting" => "unlocked",
         "is_series" => true,
         "is_series_parent" => false,
         "inventory_type" => "limited",
         "is_reserved_seating" => false,
         "show_pick_a_seat" => false,
         "show_seatmap_thumbnail" => false,
         "show_colors_in_seatmap_thumbnail" => false,
         "source" => "coyote",
         "is_free" => true,
         "version" => null,
         "summary" => "This is to test API integration",
         "facebook_event_id" => null,
         "logo_id" => null,
         "organizer_id" => "52379980083",
         "venue_id" => null,
         "category_id" => "105",
         "subcategory_id" => null,
         "format_id" => "2",
         "id" => "408827041687",
         "resource_uri" => "https://www.eventbriteapi.com/v3/events/408827041687/",
         "is_externally_ticketed" => false,
         "series_id" => "408827011597",
         "logo" => null,
       ]

The third record type we have is an event series, in our example 408827011597:

    eventbrite_one_way_sync()->session('default')->get('/series/408827011597');
    => [
         "name" => [
           "text" => "Event to test integration with my API",
           "html" => "Event to test integration with my API",
         ],
         "description" => [
           "text" => "This is to test API integration",
           "html" => "This is to test API integration",
         ],
         "url" => "https://www.eventbrite.com/e/event-to-test-integration-with-my-api-tickets-408827011597",
         "start" => [
           "timezone" => "America/Montreal",
           "local" => "2022-08-30T19:00:00",
           "utc" => "2022-08-30T23:00:00Z",
         ],
         "end" => [
           "timezone" => "America/Montreal",
           "local" => "2022-10-18T22:00:00",
           "utc" => "2022-10-19T02:00:00Z",
         ],
         "organization_id" => "1114019437563",
         "created" => "2022-08-26T16:14:02Z",
         "changed" => "2022-08-26T19:17:22Z",
         "published" => "2022-08-26T16:15:30Z",
         "capacity" => 150,
         "capacity_is_custom" => false,
         "status" => "draft",
         "currency" => "USD",
         "listed" => true,
         "shareable" => true,
         "invite_only" => false,
         "online_event" => false,
         "show_remaining" => false,
         "tx_time_limit" => 1200,
         "hide_start_date" => false,
         "hide_end_date" => false,
         "locale" => "en_US",
         "is_locked" => false,
         "privacy_setting" => "unlocked",
         "is_series" => true,
         "is_series_parent" => true,
         "inventory_type" => "limited",
         "is_reserved_seating" => false,
         "show_pick_a_seat" => false,
         "show_seatmap_thumbnail" => false,
         "show_colors_in_seatmap_thumbnail" => false,
         "source" => "coyote",
         "is_free" => true,
         "version" => null,
         "summary" => "This is to test API integration",
         "facebook_event_id" => null,
         "logo_id" => null,
         "organizer_id" => "52379980083",
         "venue_id" => null,
         "category_id" => "105",
         "subcategory_id" => null,
         "format_id" => "2",
         "id" => "408827011597",
         "resource_uri" => "https://www.eventbriteapi.com/v3/series/408827011597/",
         "logo" => null,
       ]

Initial import of existing events
-----

If you just installed this module and you have events on Eventbrite, you can run this import script, with or without a maximum. The maximum is used for testing, and it will not remember where you left off, so it will always start at the beginning.

    eventbrite_one_way_sync()->session('default')
      ->importExistingToQueue(max: 10);

    eventbrite_one_way_sync()->session('default')
      ->importExistingToQueue();



    eventbrite_one_way_sync()->session('default')
      ->eventOccurrences(function($x) {
          print_r($x . PHP_EOL);
        }, function($x) {
          print_r('Event ' . $x['id'] . '; ' . ($x['is_series'] ? 'part of series ' . $x['series_id'] : 'Single-time event') . PHP_EOL);
        }, max: 100);


How this module synchronizes events
-----

Because events which are part of series contain all the same information as the series themselves, we never fetch series information. Rather, we use the series ID as a way to tie together event occurrences.

Concretely, with the above example:

* When fetching Event 408827041687; part of series 408827011597, we create or update a node linked to Eventbrite series:408827011597.
* When fetching Event 408958615227, which is not part of a series, we create or update a node linked to Eventbrite event:408958615227.

Similar modules
-----

* [Eventbrite](https://www.drupal.org/project/eventbrite), which works only with Drupal 7, whereas our module works with Drupal >= 9.
* [Eventbrite Events](https://www.drupal.org/project/eventbrite_events), which seems to be abandoned and has no official release.




{
  "api_url": "https://www.eventbriteapi.com/{api-endpoint-to-fetch-object-details}/",
  "config": {
    "action": "test",
    "endpoint_url": "https://stewardcommunitystg.prod.acquia-sites.com/a/b/c",
    "user_id": "94081112861",
    "webhook_id": "10621979"
  }
}
{
  "api_url": "https://www.eventbriteapi.com/v3/series/408156435887/",
  "config": {
    "action": "event.created",
    "endpoint_url": "https://stewardcommunitystg.prod.acquia-sites.com/a/b/c",
    "user_id": "94081112861",
    "webhook_id": "10621979"
  }
}

{
  "api_url": "https://www.eventbriteapi.com/v3/events/408156646517/",
  "config": {
    "action": "event.updated",
    "endpoint_url": "https://stewardcommunitystg.prod.acquia-sites.com/a/b/c",
    "user_id": "94081112861",
    "webhook_id": "10621979"
  }
}

How Eventbrite stores events, how this module stores events, and what is a Remote ID?
-----

This module uses the concept of Remote ID to identify either an event with a single date, or an event with multiple dates, on Eventbrite.

On Eventbrite, these are completely separate concepts:

* An event with a single date is an Event which is not part of a series.
* An event with multiple dates is a Series which can have multiple Events.

In this module, our conception of an Event is different: it can have any number of dates.

Therefore all events (whether with a single date or multiple dates) are considered to be an event with a remote ID. The remote ID looks like this:

* default:event:123 for an event with a single date, which Eventbrite calls an event.
* default:series:123 for an event with multiple dates, which Eventbrite calls a series.

The base module (eventbrite_one_way_sync) keeps of a queue of events to process which looks like this:

| remote_id          | occurrence_id     | struct |
|--------------------|-------------------|--------|
| default:event:123  | default:event:123 | (json) |
| default:series:234 | default:event:234 | (json) |
| default:series:234 | default:event:345 | (json) |
| default:series:234 | default:event:456 | (json) |

These are actually two events (default:event:123 and default:series:234) with four total occurrences (default:event:123, default:event:234, default:event:345, default:event:456).

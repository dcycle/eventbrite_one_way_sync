[![CircleCI](https://circleci.com/gh/dcycle/eventbrite_one_way_sync/tree/1.x.svg?style=svg)](https://circleci.com/gh/dcycle/eventbrite_one_way_sync/tree/1.x)

Eventbrite Event One-Way Sync
=====

Synchronize events from Eventbrite to Drupal nodes. This only synchronizes events, not attendees, orders, ticket_classes or venues.

No GUI
-----

This module does not come with a graphical user interface.

To use this module you need to be comfortable editing your Drupal site's `settings.php` file, and use `drush` on the command line.

Here is how to set this up:

Step 1: make sure you have an Eventbrite account, website, and token
-----

* Make sure you have an Eventbrite account.
* Make sure you have a running Drupal site with this module enabled.
* Make sure your website is publicly accessible using a standard port (at the time of this writing, example.com is fine, but example.com:1234 causes errors Eventbrite) for Eventbrite to inform your website, through webhooks, when events are created or updated.
* Go to https://www.eventbrite.com/account-settings/apps and create an API key. Take note of the **private token**, it is the only information we'll use.
* You will need your organization number, which you can find by visiting the following URL.

    https://www.eventbriteapi.com/v3/users/me/organizations/?token=PUT_YOUR_PRIVATE_TOKEN_HERE

Step 2: configure this module to use your Eventbrite token
-----

Edit your site's ./sites/default/settings.php and add the following (this is not meant to be in version control; see also the "security" section, below):

```
$config['eventbrite_one_way_sync.unversioned']['api-keys'] = [
  'default' => [
    'private_token' => 'PUT_YOUR_PRIVATE_TOKEN_HERE',
    'organization_id' => '123456789',
  ],
];
```

Step 3: tell this module which node type and fields to use
-----

* Create a node type, or use an existing node type (for example "event") with at least these fields:
  * A Text (plain) field (for example "field_eventbrite_id") to store the eventbrite event ID.
  * A Text (plain, long) field (for example "field_eventbrite_struct") to store the eventbrite struct. **You should hide this field from the frontend.**
  * A **multi-value** Date Range field (for example "field_eventbrite_date") to store the eventbrite dates.

Tell Drupal what your node type and fields are (this changes the configuration of the module and can be exported to code as any configuration can):

```
drush ev "eventbrite_one_way_sync_node()->nodeConfig()->setNodeTypeAndFields('default', 'event', 'field_eventbrite_id', 'field_eventbrite_struct', 'field_eventbrite_date');"
```

Step 4: smoke-test the installation
-----

* You can now smoke-test your installation by running the following command:

    drush ev "eventbrite_one_way_sync()->smokeTest()->run(eventbrite_account_label: 'default');"

If you are getting errors, please make sure your private token is correct.

Step 5: add your webhook to Eventbrite
-----

When an event is changed or added in Eventbrite, Eventbrite informs your website by calling a predefined URL on your website with information about the event. This is a webhook.

Take note of the webhook to use by calling:

    drush ev "print_r(eventbrite_one_way_sync()->webhook(eventbrite_account_label: 'default') . PHP_EOL);"

This will give you something like:

    /webhook-receiver/eventbrite_one_way_sync/SOME_SECURITY_TOKEN?eventbrite_account_label=default

With a security token (different from your Eventbrite private token). Take note of this URL, it is your webhook.

In Eventbrite, go to https://www.eventbrite.com/account-settings/webhooks and add a webhook. Your webhook should be the full URL of your the website including the domain and the webhook path, above, for example:

    http://example.com/webhook-receiver/eventbrite_one_way_sync/SOME_SECURITY_TOKEN?eventbrite_account_label=default

**You cannot use a non-standard port at the time of this writing; Eventbrite will always fail with a 408 timeout if you do: example.com or 1.2.3.4 is OK, but not example.com:1234, or 1.2.3.4:1234.**

In Events, choose "All Events".

In Actions, select:

* event.created
* event.published
* event.unpublished
* event.updated

Save your webhook by clicking Add Webhook

Step 6: test your webhook
-----

On the Eventbrite page with your webhook, click the "Test" button Eventbrite.

**Wait 30 seconds**.

Reload the Eventbrite page with your webhook to see the test result.

You should see a test result "200 OK".

Step 7: import existing events from Eventbrite
-----

If you have events in Eventbrite which predate the installation of this module, you can queue them for import.

If you have a very large number of events, you can try importing a few before importing them all:

    drush ev "eventbrite_one_way_sync()->session('default')->importExistingToQueue(max: 10);"

This will queue your events for processing.

The actual event nodes will be created on cron. To create them run:

    drush cron

You can now visit /admin/content on your Drupal site and see your new events.

Developers: mapping extra fields from Eventbrite events to nodes
-----

All Events on Drupal represent either a single-date event on Eventbrite, or a collection of events on Eventbrite, all related to a series.

The Drupal nodes contain the eventbrite structs in their struct field, and fields are mapped during the saving of the node. Developers are encouraged to review how fields are currently mapped and create custom modules to map extra fields:

In ./modules/eventbrite_one_way_sync_node/eventbrite_one_way_sync_node.module, hook_entity_presave() is implemented. You will need to implement this hook in your custom module to do custom mapping.

Our mapping code maps:

* The title
* The event dates

The code is in ./modules/eventbrite_one_way_sync_node/src/FieldMapper/FieldMapper.php; you are encouraged to base your own custom mapping code on the code therein.

If you have existing nodes in the system and you have just implemented new mapping, you can simply resave all nodes like this:

    drush ev "eventbrite_one_way_sync_node()->nodeFactory()->resaveAllNodes('default', max: 10);"

See the "Sample extra mapping" section below.

Multiple accounts or organizations
-----

If you have multiple accounts or organizations, you can name each in your settings.php file like this:

    $config['eventbrite_one_way_sync.unversioned']['api-keys'] = [
      'default' => [
        'private_token' => 'PUT_YOUR_PRIVATE_TOKEN_HERE',
        'organization_id' => '123456789',
      ],
      'another_organization' => [
        'private_token' => 'PUT_YOUR_PRIVATE_TOKEN_HERE',
        'organization_id' => '234567890',
      ],
      'my_brothers_account' => [
        'private_token' => 'ANOTHER_ACCOUNT',
        'organization_id' => '345678901',
      ],
    ];

Then, any time you see "default" in the instructions above, use, instead, "another_organization" or "my_brothers_account".

Time zones
-----

This module maps the UTC time. To manage time zones, you will need to map a custom timezone field in your module.

See the "Sample extra mapping" section below.

Sample extra mapping
-----

Here is what your custom code might look like to map the organizer ID and timezone, if you first make sure that the fields `field_eventbrite_organizer_id` and `field_eventbrite_timezone` exist for your content type.

    <?php

    namespace Drupal\my_custom_module\EventbriteFieldMapper;

    use Drupal\eventbrite_one_way_sync_node\FieldMapper\FieldMapperInterface;
    use Drupal\Core\Entity\EntityInterface;
    use Drupal\node\NodeInterface;
    use Drupal\eventbrite_one_way_sync_node\Utilities\DependencyInjection;

    /**
     * Maps field on node save.
     */
    class EventbriteFieldMapper implements FieldMapperInterface {

      use DependencyInjection;

      /**
      * {@inheritdoc}
      */
      public function hookEntityPresave(EntityInterface $entity) {
        try {
          $struct = [];
          $eventbrite_account_label = '';

          if ($node = $this->nodeFactory()->entityToNodeAndStruct($entity, $struct, $eventbrite_account_label)) {
            // At this point the node is a valid node, we can now do our mapping.
            $this->mapOrganizerId($node, $struct, $eventbrite_account_label);
            $this->mapTimezone($node, $struct);
          }
        }
        catch (\Throwable $t) {
          // In case of any exception, do not completely break the workflow.
          $this->errorLogger()->logThrowable($t);
        }
      }

      /**
      * Map the organizer ID.
      *
      * @param \Drupal\node\NodeInterface $node
      *   A node.
      * @param array $struct
      *   An eventbrite struct.
      * @param string $eventbrite_account_label
      *   The Eventbrite account label.
      */
      public function mapOrganizerId(NodeInterface $node, array $struct, string $eventbrite_account_label) {
        $first = array_shift($struct);
        if (!empty($first['organizer_id'])) {
          $node->set('field_eventbrite_organizer_id', $first['organizer_id']);
        }
      }

      /**
      * Map the timezone.
      *
      * @param \Drupal\node\NodeInterface $node
      *   A node.
      * @param array $struct
      *   An eventbrite struct.
      */
      public function mapTimezone(NodeInterface $node, array $struct) {
        $first = array_shift($struct);
        if (!empty($first['start']['timezone'])) {
          $node->set('field_eventbrite_timezone', $first['start']['timezone']);
        }
      }

    }

Security
-----

Some setups, like Acquia, keep the sites/default/settings.php file in version control. If such is the case, you'll want to avoid having your private token there.

Option 1: include an unversioned php file from your settings file.

    # settings.php
    include('/path/to/unversioned/settings/file/outsite/web/root/settings.php');

and then put your API information in that file.

Option 2: store your private token in a text file, as suggested by the comments in Drupal's settings.php file:

    $config['eventbrite_one_way_sync.unversioned']['api-keys'] = [
      'default' => [
        'private_token' => file_get_contents('/home/example/eventbrite_private_token.txt');,
        'organization_id' => '123456789',
      ],
    ];

The field you choose to locally store the struct of your events on Eventbrite (field_eventbrite_struct in the example above) should be hidden from the frontend, for example in /admin/structure/types/manage/event/display.

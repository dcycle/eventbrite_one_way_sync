<?php

namespace Drupal\eventbrite_one_way_sync\Database;

/**
 * Represents this module's database.
 */
interface DatabaseInterface {

  /**
   * Create or update a record in the queue for a remote event.
   *
   * @param string $remote_id
   *   A remote ID such as default:series:123 or default:event:123.
   * @param string $occurrence_id
   *   An occurrence ID such as default:event:123.
   * @param array $struct
   *   The struct.
   */
  public function update(string $remote_id, string $occurrence_id, array $struct);

  /**
   * Remove a record in the queue for a remote event.
   *
   * @param string $remote_id
   *   A remote ID such as default:series:123 or default:event:123.
   */
  public function remove(string $remote_id);

}

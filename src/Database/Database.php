<?php

namespace Drupal\eventbrite_one_way_sync\Database;

use Drupal\eventbrite_one_way_sync\Utilities\DependencyInjection;
use Drupal\Component\Serialization\Json;

/**
 * Represents this module's database.
 */
class Database implements DatabaseInterface {

  use DependencyInjection;

  const TABLE = 'eventbrite_one_way_sync';

  /**
   * {@inheritdoc}
   */
  public function update(string $remote_id, string $occurrence_id, array $struct) {
    if ($this->exists($remote_id, $occurrence_id)) {
      $this->updateExisting($remote_id, $occurrence_id, $struct);
    }
    else {
      $this->insert($remote_id, $occurrence_id, $struct);
    }
  }

  /**
   * Check whether a record already exists.
   *
   * @param string $remote_id
   *   A remote ID such as default:series:123 or default:event:123.
   * @param string $occurrence_id
   *   An occurrence ID such as default:event:123.
   *
   * @return bool
   *   TRUE if this already exists.
   */
  public function exists(string $remote_id, string $occurrence_id) : bool {
    $query = $this->connection()->select(self::TABLE, self::TABLE);
    $query->fields(self::TABLE, [
      'remote_id' => 'remote_id',
    ]);
    $query->condition('remote_id', $remote_id);
    $query->condition('occurrence_id', $occurrence_id);
    $query->range(0, 1);
    return count($query->execute()->fetchAll()) > 0;
  }

  /**
   * Update an existing record with a new struct.
   *
   * @param string $remote_id
   *   A remote ID such as default:series:123 or default:event:123.
   * @param string $occurrence_id
   *   An occurrence ID such as default:event:123.
   * @param array $struct
   *   The struct.
   */
  public function updateExisting(string $remote_id, string $occurrence_id, array $struct) {
    $this->connection()->update(self::TABLE)
      ->fields([
        'struct' => Json::encode($struct),
      ])
      ->condition('remote_id', $remote_id)
      ->condition('occurrence_id', $occurrence_id)
      ->execute();
  }

  /**
   * Insert a new record in the queue.
   *
   * @param string $remote_id
   *   A remote ID such as default:series:123 or default:event:123.
   * @param string $occurrence_id
   *   An occurrence ID such as default:event:123.
   * @param array $struct
   *   The struct.
   */
  public function insert(string $remote_id, string $occurrence_id, array $struct) {
    $this->connection()->insert(self::TABLE)
      ->fields([
        'remote_id' => $remote_id,
        'occurrence_id' => $occurrence_id,
        'struct' => Json::encode($struct),
      ])
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function remove(string $remote_id) {
    $this->connection()->delete(self::TABLE)
      ->condition('remote_id', $remote_id)
      ->execute();
  }

}

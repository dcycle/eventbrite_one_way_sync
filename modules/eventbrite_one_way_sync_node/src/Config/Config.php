<?php

namespace Drupal\eventbrite_one_way_sync_node\Config;

use Drupal\eventbrite_one_way_sync\Utilities\Singleton;
use Drupal\eventbrite_one_way_sync\Config\Config as BaseConfig;
use Drupal\Component\Serialization\Json;

/**
 * Wrapper around configuration.
 */
class Config extends BaseConfig implements ConfigInterface {

  use Singleton;

  /**
   * {@inheritdoc}
   */
  public function fieldMap(string $eventbrite_account_label) : array {
    $this->assertNonEmptyString($eventbrite_account_label, 'Eventbrite account label must be non-empty, for example default.');

    $mapping = $this->getFieldMapping();

    if (!array_key_exists($eventbrite_account_label, $mapping)) {
      throw new \Exception('The eventbrite account label ' . $eventbrite_account_label . ' does not exist in mapping. Available account labels are ' . Json::encode($mapping));
    }

    $this->checkNodeTypeAndFields(
      eventbrite_account_label: $eventbrite_account_label,
      node_type: $mapping[$eventbrite_account_label]['node_type'],
      eventbrite_id_field: $mapping[$eventbrite_account_label]['id_field'],
      eventbrite_struct_field: $mapping[$eventbrite_account_label]['struct_field'],
      eventbrite_date_field: $mapping[$eventbrite_account_label]['date_field'],
    );

    return $mapping[$eventbrite_account_label];
  }

  /**
   * Get a field mapping information line for an account.
   *
   * @param string $eventbrite_account_label
   *   An Eventbrite account such as "default".
   * @param string $item
   *   An item such as node_type or id_field.
   *
   * @return string
   *   The value.
   */
  public function fieldMapItem(string $eventbrite_account_label, string $item) {
    $this->assertNonEmptyString($eventbrite_account_label, 'Eventbrite account label must be non-empty, for example default.');
    $this->assertNonEmptyString($item, 'Item cannot be empty; try node_type or id_field.');

    $map = $this->fieldMap($eventbrite_account_label);

    if (empty($map[$item])) {
      throw new \Exception('map[' . $eventbrite_account_label . '][map][' . $item . '] cannot be empty.');
    }

    $candidate = $map[$item];

    return $candidate;
  }

  /**
   * {@inheritdoc}
   */
  public function nodeType(string $eventbrite_account_label) : string {
    return $this->fieldMapItem($eventbrite_account_label, 'node_type');
  }

  /**
   * {@inheritdoc}
   */
  public function idField(string $eventbrite_account_label) : string {
    return $this->fieldMapItem($eventbrite_account_label, 'id_field');
  }

  /**
   * {@inheritdoc}
   */
  public function checkNodeTypeAndFields(string $eventbrite_account_label, string $node_type = '', string $eventbrite_id_field = '', string $eventbrite_struct_field = '', $eventbrite_date_field = '') {
    $this->assertNonEmptyString($eventbrite_account_label, 'Label cannot be empty, it should be something like "default"');

    foreach ([
      'Node type' => [
        'var' => $node_type,
        'example' => 'event',
      ],
      'Eventbrite ID field' => [
        'var' => $eventbrite_id_field,
        'example' => 'field_eventbrite_id',
      ],
      'Eventbrite struct field' => [
        'var' => $eventbrite_struct_field,
        'example' => 'field_eventbrite_struct',
      ],
      'Eventbrite date field' => [
        'var' => $eventbrite_date_field,
        'example' => 'field_eventbrite_date',
      ],
    ] as $eventbrite_account_label => $info) {
      $this->assertNonEmptyString($info['var'], $eventbrite_account_label . ' cannot be empty. It can be something like ' . $info['example']);
      if ($info['var'] == 'NOT SET') {
        throw new \Exception($info['var'] . " cannot be NOT SET (that's the default value; you need to change it). It can be something like " . $info['example']);
      }
    }

    $this->assertNodeTypeExists($node_type);
    $this->assertFieldExists($eventbrite_id_field, $node_type);
    $this->assertFieldExists($eventbrite_struct_field, $node_type);
    $this->assertFieldExists($eventbrite_date_field, $node_type);
  }

  /**
   * {@inheritdoc}
   */
  public function assertNodeTypeExists(string $node_type) {
    $this->assertNonEmptyString($node_type, 'Node type cannot be empty, it should be something like "event" and it must exist.');

    $entityTypeManager = $this->entityTypeManager();

    if (!$entityTypeManager->getStorage('node_type')->load($node_type)) {
      throw new \Exception('Node type ' . $node_type . ' does not seem to exist, it should be something like "event" and it must exist.');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function assertFieldExists(string $field_name, string $node_type) {
    $this->assertNonEmptyString($field_name, 'Field name cannot be empty, it should be something like field_xyz.');
    $this->assertNonEmptyString($node_type, 'Node type cannot be empty, it should be something like "event" and it must exist.');
    $fields = $this->entityFieldManager()->getFieldDefinitions('node', $node_type);
    if (!array_key_exists($field_name, $fields)) {
      throw new \Exception('We were expecting the field ' . $field_name . ' to exist in node type ' . $node_type . ', but it is not in ' . implode(', ', array_keys($fields)));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldMapping() : array {
    $candidate = $this->configFactory()
      ->get('eventbrite_one_way_sync.versioned')
      ->get('mapping');

    $this->assertNonEmptyArray($candidate, 'The mapping in configuration (eventbrite_one_way_sync.versioned mapping) should be a non-empty array. If it is empty, this module will not do anything.');

    return $candidate;
  }

  /**
   * {@inheritdoc}
   */
  public function setNodeTypeAndFields(string $eventbrite_account_label, string $node_type, string $eventbrite_id_field, string $eventbrite_struct_field, string $eventbrite_date_field) {
    $this->checkNodeTypeAndFields(
      eventbrite_account_label: $eventbrite_account_label,
      node_type: $node_type,
      eventbrite_id_field: $eventbrite_id_field,
      eventbrite_struct_field: $eventbrite_struct_field,
      eventbrite_date_field: $eventbrite_date_field,
    );

    $mapping = $this->getFieldMapping();
    $mapping[$eventbrite_account_label] = [
      'node_type' => $node_type,
      'id_field' => $eventbrite_id_field,
      'struct_field' => $eventbrite_struct_field,
      'date_field' => $eventbrite_date_field,
    ];

    $this->setFieldMapping($mapping);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteNodeTypeMapping(string $eventbrite_account_label) {
    $this->assertNonEmptyString($eventbrite_account_label, 'Label cannot be empty, it should be something like default.');

    $mapping = $this->getFieldMapping();
    unset($mapping[$eventbrite_account_label]);

    $this->setFieldMapping($mapping);
  }

  /**
   * {@inheritdoc}
   */
  public function setFieldMapping(array $mapping) {
    $config = $this->configFactory()
      ->getEditable('eventbrite_one_way_sync.versioned');
    $config->set('mapping', $mapping)->save();
  }

}

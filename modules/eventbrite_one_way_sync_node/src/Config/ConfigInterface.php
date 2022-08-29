<?php

namespace Drupal\eventbrite_one_way_sync_node\Config;

/**
 * Wrapper around configuration.
 */
interface ConfigInterface {

  /**
   * Get field mapping for a specific eventbrite account.
   *
   * @param string $eventbrite_account_label
   *   An Eventbrite account label such as default.
   *
   * @return array
   *   Mapping for required fields for this specific account.
   */
  public function fieldMap(string $eventbrite_account_label) : array;

  /**
   * Get node tyoe for a specific eventbrite account.
   *
   * @param string $eventbrite_account_label
   *   An Eventbrite account label such as default.
   *
   * @return string
   *   Node type.
   */
  public function nodeType(string $eventbrite_account_label) : string;

  /**
   * Get ID field for a specific eventbrite account.
   *
   * @param string $eventbrite_account_label
   *   An Eventbrite account label such as default.
   *
   * @return string
   *   ID field.
   */
  public function idField(string $eventbrite_account_label) : string;

  /**
   * Throw an exception if the node information is incorrect.
   *
   * @param string $eventbrite_account_label
   *   An Eventbrite account such as "default".
   * @param string $node_type
   *   An existing node type (for example "event")
   * @param string $eventbrite_id_field
   *   A Text (plain) field (for example "field_eventbrite_id") to store the
   *   eventbrite event ID.
   * @param string $eventbrite_struct_field
   *   A Text (plain, long) field (for example "field_eventbrite_struct") to
   *   store the eventbrite struct.
   */
  public function checkNodeTypeAndFields(string $eventbrite_account_label, string $node_type = '', string $eventbrite_id_field = '', string $eventbrite_struct_field = '');

  /**
   * Throw an exception if a node type does not exist.
   *
   * @param string $node_type
   *   A node type.
   */
  public function assertNodeTypeExists(string $node_type);

  /**
   * Throw an exception if a field does not exist within a node type.
   *
   * @param string $field_name
   *   A field name.
   * @param string $node_type
   *   A node type.
   */
  public function assertFieldExists(string $field_name, string $node_type);

  /**
   * Get all field mappings keyed by Eventbrite account id.
   *
   * @return array
   *   Array keyed by Eventbrite account id; each item is also an array keyed
   *   by required field (node_type, id_field, struct_field, date_field)
   *   and having the field name as a value.
   */
  public function getFieldMapping() : array;

  /**
   * Set node type and field mapping for an Eventbrite account label.
   *
   * @param string $eventbrite_account_label
   *   An Eventbrite account label, for example "default".
   * @param string $node_type
   *   A node type.
   * @param string $eventbrite_id_field
   *   An Eventbrite ID field (Drupal field existing in node type).
   * @param string $eventbrite_struct_field
   *   An Eventbrite struct field (Drupal field existing in node type).
   * @param string $eventbrite_date_field
   *   An Eventbrite multiple date field.
   */
  public function setNodeTypeAndFields(string $eventbrite_account_label, string $node_type, string $eventbrite_id_field, string $eventbrite_struct_field, string $eventbrite_date_field);

  /**
   * Delete node type and field mapping for an Eventbrite account label.
   *
   * @param string $eventbrite_account_label
   *   An Eventbrite account label, for example "default".
   */
  public function deleteNodeTypeMapping(string $eventbrite_account_label);

  /**
   * Set all field mappings keyed by Eventbrite account id.
   *
   * @param array $mapping
   *   Array keyed by Eventbrite account id; each item is also an array keyed
   *   by required field (node_type, id_field, struct_field, date_field)
   *   and having the field name as a value.
   */
  public function setFieldMapping(array $mapping);

}

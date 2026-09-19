<?php

namespace Drupal\event_order_schedule\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'event_order_schedule' field type.
 *
 * @FieldType(
 *   id = "event_order_schedule",
 *   label = @Translation("Event Order Schedule"),
 *   description = @Translation("Stores event and ordering schedule information."),
 *   category = @Translation("Custom"),
 *   default_widget = "event_order_schedule_widget",
 *   default_formatter = "event_order_schedule_formatter"
 * )
 */
class EventOrderScheduleItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties = [];

    $properties['event_start'] = DataDefinition::create('string')
      ->setLabel(t('Event start'));

    $properties['preorder_start'] = DataDefinition::create('string')
      ->setLabel(t('Pre-order starts'));

    $properties['preorder_end'] = DataDefinition::create('string')
      ->setLabel(t('Pre-order ends'));

    $properties['preorder_price'] = DataDefinition::create('string')
      ->setLabel(t('Pre-order price'));

    $properties['regular_start'] = DataDefinition::create('string')
      ->setLabel(t('Regular order starts'));

    $properties['regular_end'] = DataDefinition::create('string')
      ->setLabel(t('Regular order ends'));

    $properties['regular_price'] = DataDefinition::create('string')
      ->setLabel(t('Regular price'));

    $properties['event_end'] = DataDefinition::create('string')
      ->setLabel(t('Event end'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'event_start' => [
          'type' => 'varchar',
          'length' => 10,
          'not null' => TRUE,
        ],
        'preorder_start' => [
          'type' => 'varchar',
          'length' => 10,
          'not null' => FALSE,
        ],
        'preorder_end' => [
          'type' => 'varchar',
          'length' => 10,
          'not null' => FALSE,
        ],
        'preorder_price' => [
          'type' => 'numeric',
          'precision' => 12,
          'scale' => 2,
          'not null' => FALSE,
        ],
        'regular_start' => [
          'type' => 'varchar',
          'length' => 10,
          'not null' => TRUE,
        ],
        'regular_end' => [
          'type' => 'varchar',
          'length' => 10,
          'not null' => FALSE,
        ],
        'regular_price' => [
          'type' => 'numeric',
          'precision' => 12,
          'scale' => 2,
          'not null' => TRUE,
        ],
        'event_end' => [
          'type' => 'varchar',
          'length' => 10,
          'not null' => TRUE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function mainPropertyName() {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    return empty($this->event_start)
      && empty($this->event_end);
  }

}
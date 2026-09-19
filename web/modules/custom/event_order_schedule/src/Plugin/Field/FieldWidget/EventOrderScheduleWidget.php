<?php

namespace Drupal\event_order_schedule\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'event_order_schedule_widget' widget.
 *
 * @FieldWidget(
 *   id = "event_order_schedule_widget",
 *   label = @Translation("Event Order Schedule"),
 *   field_types = {
 *     "event_order_schedule"
 *   }
 * )
 */
class EventOrderScheduleWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(
    FieldItemListInterface $items,
    $delta,
    array $element,
    array &$form,
    FormStateInterface $form_state
  ) {
    $item = $items[$delta] ?? NULL;

    $element['#attached']['library'][] = 'event_order_schedule/widget';

    $element['#attributes']['class'][] = 'event-order-schedule-widget';

    $element['event_start'] = [
      '#type' => 'date',
      '#title' => $this->t('Event start'),
      '#required' => TRUE,
      '#default_value' => $item?->event_start ?? '',
      '#attributes' => [
        'class' => ['event-order-schedule-field'],
      ],
    ];

    $element['event_end'] = [
      '#type' => 'date',
      '#title' => $this->t('Event end'),
      '#required' => TRUE,
      '#default_value' => $item?->event_end ?? '',
      '#wrapper_attributes' => [
        'class' => ['event-order-schedule-field'],
      ],
    ];

    $element['title_pre_order'] = [
      '#markup' => '<p class="event-order-schedule__title">' .
        $this->t('Pre-order') .
        '</p>',
    ];

    $element['description_pre_order'] = [
      '#markup' => '<p class="event-order-schedule__desc">' .
        $this->t('[Optional] This set of dates must be before the event start and regular order date.') .
        '</p>',
    ];

    $element['preorder_start'] = [
      '#type' => 'date',
      '#title' => $this->t('Pre-order starts'),
      '#required' => FALSE,
      '#default_value' => $item?->preorder_start ?? '',
      '#wrapper_attributes' => [
        'class' => ['event-order-schedule-field'],
      ],
    ];

    $element['preorder_end'] = [
      '#type' => 'date',
      '#title' => $this->t('Pre-order ends'),
      '#required' => FALSE,
      '#default_value' => $item?->preorder_end ?? '',
      '#wrapper_attributes' => [
        'class' => ['event-order-schedule-field'],
      ],
    ];

    $element['preorder_price'] = [
      '#type' => 'number',
      '#title' => $this->t('Pre-order price'),
      '#step' => '0.01',
      '#min' => '0',
      '#default_value' => $item?->preorder_price ?? '',
      '#wrapper_attributes' => [
        'class' => ['event-order-schedule-field'],
      ],
    ];


    $element['title_regular'] = [
      '#markup' => '<p class="event-order-schedule__title">' .
        $this->t('Regular') .
        '</p>',
    ];
    
    $element['description_regular'] = [
      '#markup' => '<p class="event-order-schedule__desc">' .
        $this->t('The start for regular could be prior or within the event starting date.') .
        '</p>',
    ];

    $element['regular_start'] = [
      '#type' => 'date',
      '#title' => $this->t('Regular order starts'),
      '#required' => TRUE,
      '#default_value' => $item?->regular_start ?? '',
      '#wrapper_attributes' => [
        'class' => ['event-order-schedule-field'],
      ],
    ];

    $element['regular_end'] = [
      '#type' => 'date',
      '#title' => $this->t('Regular order ends'),
      '#required' => FALSE,
      '#default_value' => $item?->regular_end ?? '',
      '#wrapper_attributes' => [
        'class' => ['event-order-schedule-field'],
      ],
    ];

    $element['regular_price'] = [
      '#type' => 'number',
      '#title' => $this->t('Regular price'),
      '#required' => TRUE,
      '#step' => '0.01',
      '#min' => '0',
      '#default_value' => $item?->regular_price ?? '',
      '#wrapper_attributes' => [
        'class' => ['event-order-schedule-field'],
      ],
    ];
    return $element;
  }

}
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
      '#element_validate' => [
        [static::class, 'validateEventOrderSchedule'],
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
      '#element_validate' => [
        [static::class, 'validateEventOrderSchedule'],
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
      '#element_validate' => [
        [static::class, 'validateEventOrderSchedule'],
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

  /**
 * {@inheritdoc}
 */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as &$value) {

      // Convert empty optional numeric/date values to NULL.
      if ($value['preorder_start'] === '') {
        $value['preorder_start'] = NULL;
      }

      if ($value['preorder_end'] === '') {
        $value['preorder_end'] = NULL;
      }

      if ($value['preorder_price'] === '') {
        $value['preorder_price'] = NULL;
      }

      if ($value['regular_end'] === '') {
        $value['regular_end'] = NULL;
      }
    }

    return $values;
  }

  public static function validateEventOrderSchedule(
    array &$element,
    FormStateInterface $form_state,
    array &$complete_form
  ): void {
    $values = $form_state->getValue($element['#parents']);

    $preorder_start = $values['preorder_start'] ?? NULL;
    $preorder_end = $values['preorder_end'] ?? NULL;
    $preorder_price = $values['preorder_price'] ?? NULL;

    // Pre-order is optional.
    $has_preorder_dates =
      $preorder_start !== NULL &&
      $preorder_start !== '';

    if ($has_preorder_dates) {
      // Price is required when pre-order is configured.
      if ($preorder_price === NULL || $preorder_price === '') {
        $form_state->setError(
          $element['preorder_price'],
          t('Pre-order price cannot be empty. Enter 0 if the pre-order is not applicable or free.')
        );
      }
    }

    // Optional: require both pre-order dates.
    if ($preorder_start !== '' && $preorder_end === '' && $preorder_price > 0) {
      $form_state->setError(
        $element['preorder_end'],
        t('Pre-order end date is required when a pre-order start date / pre-order price is provided.')
      );
    }

    if ($preorder_end !== '' && $preorder_start === '' && $preorder_price > 0) {
      $form_state->setError(
        $element['preorder_start'],
        t('Pre-order start date is required when a pre-order end date / pre-order price is provided.')
      );
    }
  }

}
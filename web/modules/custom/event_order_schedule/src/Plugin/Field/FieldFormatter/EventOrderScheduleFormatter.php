<?php

namespace Drupal\event_order_schedule\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Plugin implementation of the 'event_order_schedule_formatter'.
 *
 * @FieldFormatter(
 *   id = "event_order_schedule_formatter",
 *   label = @Translation("Event Order Schedule"),
 *   field_types = {
 *     "event_order_schedule"
 *   }
 * )
 */
class EventOrderScheduleFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(
    FieldItemListInterface $items,
    $langcode
  ) {

    $elements = [];

    foreach ($items as $delta => $item) {

      $today = new DrupalDateTime('now');
      $today->setTime(0, 0, 0);

      $event_start = $this->date($item->event_start);
      $preorder_start = $this->date($item->preorder_start);
      $preorder_end = $this->date($item->preorder_end);
      $regular_start = $this->date($item->regular_start);
      $regular_end = $this->date($item->regular_end);
      $event_end = $this->date($item->event_end);

      /*
       * Event ended.
       */
      if ($event_end && $today > $event_end) {
        $elements[$delta] = [
          '#theme' => 'event_order_schedule',
          '#state' => 'ended',
          '#event_start' => NULL,
          '#price' => NULL,
          '#button' => NULL,
        ];

        continue;
      }

      /*
       * Pre-order period.
       *
       * pre-order start <= today
       * AND
       * today <= pre-order end
       */
      $is_preorder = FALSE;

      if ($preorder_start) {
        $is_preorder = $today >= $preorder_start;

        if ($preorder_end) {
          $is_preorder = $is_preorder && $today <= $preorder_end;
        }

        /*
         * Regular ordering should take priority once
         * regular_start has arrived.
         */
        if ($regular_start && $today >= $regular_start) {
          $is_preorder = FALSE;
        }
      }

      if ($is_preorder) {
        $elements[$delta] = [
          '#theme' => 'event_order_schedule',
          '#state' => 'preorder',
          '#event_start' => $this->formatDate($event_start),
          '#price' => $item->preorder_price,
          '#button' => [
            'text' => 'Pre-order now',
            'url' => '#',
          ],
        ];

        continue;
      }

      /*
       * Before pre-order begins.
       */
      if (
        $preorder_start &&
        $today < $preorder_start
      ) {
        $elements[$delta] = [
          '#theme' => 'event_order_schedule',
          '#state' => 'upcoming',
          '#event_start' => $this->formatDate($event_start),
          '#preorder_start' => $this->formatDate($preorder_start),
          '#price' => $item->preorder_price,
          '#button' => NULL,
        ];

        continue;
      }

      /*
       * Regular ordering.
       */
      if (
        $regular_start &&
        $today >= $regular_start
      ) {

        $can_order = TRUE;

        if ($regular_end && $today > $regular_end) {
          $can_order = FALSE;
        }

        $elements[$delta] = [
          '#theme' => 'event_order_schedule',
          '#state' => 'regular',
          '#event_start' => $this->formatDate($event_start),
          '#price' => $item->regular_price,
          '#button' => $can_order
            ? [
                'text' => 'Order now',
                'url' => '#',
              ]
            : NULL,
        ];

        continue;
      }

      /*
       * Fallback.
       */
      $elements[$delta] = [
        '#theme' => 'event_order_schedule',
        '#state' => 'upcoming',
        '#event_start' => $this->formatDate($event_start),
        '#preorder_start' => $this->formatDate($preorder_start),
        '#price' => $item->preorder_price,
        '#button' => NULL,
      ];
    }

    return $elements;
  }

  /**
   * Convert string to DrupalDateTime.
   */
  protected function date(?string $value): ?DrupalDateTime {
    if (empty($value)) {
      return NULL;
    }

    return new DrupalDateTime($value);
  }

  /**
   * Format date for display.
   */
  protected function formatDate(?DrupalDateTime $date): ?string {
    if (!$date) {
      return NULL;
    }

    return $date->format('F j, Y');
  }

}
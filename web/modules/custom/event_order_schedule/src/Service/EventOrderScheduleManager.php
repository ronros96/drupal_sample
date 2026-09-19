<?php

namespace Drupal\event_order_schedule\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

class EventOrderScheduleManager {

  protected $entityTypeManager;

  protected $logger;

  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    LoggerChannelFactoryInterface $logger_factory
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->logger = $logger_factory->get('event_order_schedule');
  }

  /**
   * Synchronize node publishing status.
   */
  public function synchronize() {

    $storage = $this->entityTypeManager
      ->getStorage('node');

    $query = $storage
      ->getQuery()
      ->accessCheck(FALSE);

    $nids = $query
      ->condition('type', 'YOUR_CONTENT_TYPE')
      ->exists('field_event_schedule')
      ->execute();

    if (!$nids) {
      return;
    }

    $nodes = $storage->loadMultiple($nids);

    $today = new \DateTimeImmutable('today');

    foreach ($nodes as $node) {

      if (!$node->hasField('field_event_schedule')) {
        continue;
      }

      $items = $node->get('field_event_schedule')->getValue();

      if (empty($items[0])) {
        continue;
      }

      $schedule = $items[0];

      if (
        empty($schedule['event_start']) ||
        empty($schedule['event_end'])
      ) {
        continue;
      }

      $event_start = new \DateTimeImmutable(
        $schedule['event_start']
      );

      $event_end = new \DateTimeImmutable(
        $schedule['event_end']
      );

      $should_publish =
        $today >= $event_start &&
        $today <= $event_end;

      if ($should_publish && !$node->isPublished()) {
        $node->setPublished();
        $node->save();

        $this->logger->notice(
          'Node @nid automatically published.',
          [
            '@nid' => $node->id(),
          ]
        );
      }

      if (!$should_publish && $node->isPublished()) {
        $node->setUnpublished();
        $node->save();

        $this->logger->notice(
          'Node @nid automatically unpublished.',
          [
            '@nid' => $node->id(),
          ]
        );
      }
    }
  }

}
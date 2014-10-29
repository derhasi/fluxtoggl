<?php

/**
 * @file
 * Contains TimeEntry.
 */

namespace Drupal\fluxtoggl\Plugin\Entity;

use Drupal\fluxservice\Entity\RemoteEntity;

/**
 * Entity class for feed entries.
 */
class TimeEntry extends RemoteEntity implements TimeEntryInterface {

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxtoogl_time_entry',
      'label' => t('Toggl: Time entry'),
      'service' => 'fluxtoggl',
      'controller class' => '\Drupal\fluxtoggl\TimeEntryController',
      'label callback' => 'entity_class_label',
      'entity keys' => array(
        'id' => 'drupal_entity_id',
        'remote id' => 'id',
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function getEntityPropertyInfo($entity_type, $entity_info) {

    $info['description'] = array(
      'label' => t('Description'),
      'description' => t("The entry's description."),
      'type' => 'text',
      'getter callback' => 'fluxservice_entity_property_getter_method',
    );

    return $info;
  }

}

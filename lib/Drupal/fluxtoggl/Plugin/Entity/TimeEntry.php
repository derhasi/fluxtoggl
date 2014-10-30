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
      'name' => 'fluxtoggl_time_entry',
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
   *
   * @see https://github.com/toggl/toggl_api_docs/blob/master/chapters/time_entries.md
   */
  public static function getEntityPropertyInfo($entity_type, $entity_info) {

    $info['description'] = array(
      'label' => t('Description'),
      'description' => t("The entry's description."),
      'type' => 'text',
      //'getter callback' => 'fluxservice_entity_property_getter_method',
    );

    $info['wid'] = array(
      'label' => t('Workspace'),
      'description' => t('The Toggl workspace this time entry is posted to.'),
      'type' => 'fluxtoggl_workspace',
    );

    $info['pid'] = array(
      'label' => t('Project'),
      'description' => t('The Toggl project this time entry is posted to.'),
      'type' => 'fluxtoggl_project',
    );

    $info['tid'] = array(
      'label' => t('Task'),
      'description' => t('The Toggl task this time entry is posted to.'),
      'type' => 'fluxtoggl_task',
    );

    $info['billable'] = array(
      'label' => t('Billable'),
      'description' => t('Is this time entry billable? (pro only)'),
      'type' => 'boolean',
    );

    $info['start'] = array(
      'label' => t('Start date'),
      'description' => t('The time for when the time entry was started.'),
      'type' => 'date',
      'getter callback' => 'entity_property_verbatim_date_get',
    );

    $info['stop'] = array(
      'label' => t('Stop date'),
      'description' => t('The time for when the time entry was stopped.'),
      'type' => 'date',
      'getter callback' => 'entity_property_verbatim_date_get',
    );

    $info['duration'] = array(
      'label' => t('Duration'),
      'description' => t('Time entry duration in seconds.'),
      'type' => 'integer',
    );

    $info['created_with'] = array(
      'label' => t('Created with'),
      'description' => t('The name of your client app'),
      'type' => 'string',
    );

    $info['tags'] = array(
      'label' => t('Tags'),
      'description' => t('A list of tag names'),
      'type' => 'list<string>',
    );

    $info['duronly'] = array(
      'label' => t('Duration only'),
      'description' => t('Should Toggl show the start and stop time of this time entry?'),
      'type' => 'boolean',
    );

    $info['at'] = array(
      'label' => t('Last modified date'),
      'description' => t('The timestamp that is sent in the response, indicates the time item was last updated.'),
      'type' => 'date',
      'getter callback' => 'entity_property_verbatim_date_get',
    );

    return $info;
  }

}

<?php

/**
 * @file
 * Contains TimeEntryEventHandler.
 */

namespace Drupal\fluxtoggl\Plugin\Rules\EventHandler;

use Drupal\fluxservice\Rules\DataUI\AccountEntity;
use Drupal\fluxservice\Rules\EventHandler\CronEventHandlerBase;

/**
 * Cron-based feed entry event handler.
 */
class TimeEntryEventHandler extends CronEventHandlerBase {

  /**
   * Defines the event.
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxtoggl_time_entry',
      'label' => t('A new time entry appeared'),
      'category' => 'fluxtoggl',
      'access callback' => array(get_called_class(), 'integrationAccess'),
      'variables' => array(
        'account' => array(
          'type' => 'fluxservice_account',
          'bundle' => 'fluxtoggl',
          'label' => t('Toggl account'),
          'description' => t('The account used for authenticating with Toggl API.'),
        ),
        'time_entry' => array(
          'label' => t('Time entry'),
          'type' => 'fluxtoggl_time_entry',
          'description' => t('The newly discovered time entry.'),
        ),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getTaskHandler() {
    return 'Drupal\fluxtoggl\Task\TimeEntryTaskHandler';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array &$form_state) {
    $settings = $this->getSettings();

    $form['account'] = array(
      '#type' => 'select',
      '#title' => t('Account'),
      '#description' => t('The service account used for authenticating with the Toggl API.'),
      '#options' => AccountEntity::getOptions('fluxtoggl', $form_state['rules_config']),
      '#default_value' => $settings['account'],
      '#required' => TRUE,
      '#empty_value' => '',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaults() {
    return array(
      'account' => NULL,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    return 'TimeEntryEventHandler';
  }

  /**
   * {@inheritdoc}
   */
  public function getEventNameSuffix() {
    return $this->settings['account'];
  }
}

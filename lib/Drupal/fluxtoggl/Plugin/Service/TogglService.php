<?php

/**
 * @file
 * Contains TogglService.
 */

namespace Drupal\fluxtoggl\Plugin\Service;

use Drupal\fluxservice\Plugin\Entity\Service;

/**
 * Service plugin implementation for Twitter.
 */
class TogglService extends Service implements TogglServiceInterface {

  /**
   * Defines the plugin.
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxtoggl',
      'label' => t('Toggl'),
      'description' => t('Provides Toggl integration for fluxkraft.'),
      'class' => '\Drupal\fluxtoggl\Plugin\Service\TogglService',
      'icon font class' => 'icon-toggl',
      'icon background color' => '#DF2A36'
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultSettings() {
    return parent::getDefaultSettings() + array(
      'workspace' => '',
      'polling_interval' => 900,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array &$form_state) {
    $form = parent::settingsForm($form_state);

    $form['help'] = array(
      '#type' => 'markup',
      '#markup' => t('In the following, you need to provide authentication details for communicating with Twitter.<br/>For that, you have to create an application in the <a href="https://dev.twitter.com/apps">Twitter app management interface</a> and provide its consumer key and secret below.'),
      '#prefix' => '<p class="fluxservice-help">',
      '#suffix' => '</p>',
      '#weight' => -1,
    );

    $form['workspace'] = array(
      '#type' => 'textfield',
      '#title' => t('Workspace ID'),
      '#default_value' => $this->getWorkspaceID(),
    );

    $form['rules'] = array(
      '#type' => 'fieldset',
      '#title' => t('Event detection'),
      '#description' => t('Settings for detecting Twitter related events as configured via <a href="@url">Rules</a>.', array('@url' => url('http://drupal.org/project/rules'))),
      // Avoid the data being nested below 'rules' or falling out of 'data'.
      '#parents' => array('data'),
    );

    $form['rules']['polling_interval'] = array(
      '#type' => 'select',
      '#title' => t('Polling interval'),
      '#default_value' => $this->getPollingInterval(),
      '#options' => array(0 => t('Every cron run')) + drupal_map_assoc(array(300, 900, 1800, 3600, 10800, 21600, 43200, 86400, 604800), 'format_interval'),
      '#description' => t('The time to wait before checking for updates. Note that the effecitive update interval is limited by how often the cron maintenance task runs. Requires a correctly configured <a href="@cron">cron maintenance task</a>.', array('@cron' => url('admin/reports/status'))),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getPollingInterval() {
    return $this->data->get('polling_interval');
  }

  /**
   * {@inheritdoc}
   */
  public function setPollingInterval($interval) {
    $this->data->set('polling_interval', $interval);
    return $this;
  }

  public function getWorkspaceID() {
    return $this->data->get('workspace');
  }

  public function setWorkspaceID($workspace) {
    $this->data->set('workspace', $workspace);
    return $this;
  }

}

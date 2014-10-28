<?php

/**
 * @file
 * Contains Drupal\fluxtoggl\Plugin\Service\TogglAccount.
 */

namespace Drupal\fluxtoggl\Plugin\Service;

use Drupal\fluxservice\Plugin\Entity\Account;

class TogglAccount extends Account implements TogglAccountInterface {

  /**
   * Defines the plugin.
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxtoggl',
      'label' => t('Toggl account'),
      'description' => t('Provides Toggl integration for fluxkraft.'),
      'service' => 'fluxtoggl',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultSettings() {
    return array(
      'api_token' => '',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array &$form_state) {
    $form = parent::settingsForm($form_state);

    $form['api_token'] = array(
      '#type' => 'textfield',
      '#title' => t('API Token'),
      '#default_value' => $this->getAPIToken(),
      '#description' => t('Enter your API token to provide access to your Toggl account. Your API Token is available in @profile.', array(
        '@profile' => l(t('your Toggl profile'), 'https://www.toggl.com/app/profile'),
      )),
    );

    return $form;
  }

  /**
   * Returns API token for the account.
   *
   * @return string
   */
  public function getAPIToken() {
    return $this->data->get('api_token');
  }

  /**
   * Sets the API token for account.
   *
   * @param string $api_token
   *  API token
   *
   * @return $this
   */
  public function setAPIToken($api_token) {
    $this->data->set('api_token', $api_token);
    return $this;
  }

}

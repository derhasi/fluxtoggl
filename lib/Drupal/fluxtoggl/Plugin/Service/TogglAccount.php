<?php

/**
 * @file
 * Contains Drupal\fluxtoggl\Plugin\Service\TogglAccount.
 */

namespace Drupal\fluxtoggl\Plugin\Service;

use AJT\Toggl\TogglClient;
use Drupal\fluxservice\Plugin\Entity\Account;

/**
 * Class TogglAccount
 */
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
   * {@inheritdoc}
   */
  public function settingsFormValidate(array $form, array &$form_state) {
    parent::settingsFormValidate($form, $form_state);

    // Check if the API token provides us with a valid user.
    $api_token = $form_state['values']['data']['api_token'];
    $toggl_client = TogglClient::factory(array('api_key' => $api_token));

    try {
      // Check if a response can be made without throwing a Response exception.
      $toggl_client->getCurrentUser();
    }
    catch (\Guzzle\HTTP\Exception\BadResponseException $e) {
      form_set_error('data][api_token', t('The Toggl API token is not valid.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function settingsFormSubmit(array $form, array &$form_state) {
    parent::settingsFormSubmit($form, $form_state);

    // Each user has a Toggl ID and this is stored as remote identifier.
    $toggl_client = TogglClient::factory(array('api_key' => $this->getAPIToken()));
    $current_user = $toggl_client->getCurrentUser();
    $this->setRemoteIdentifier($current_user['id']);
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

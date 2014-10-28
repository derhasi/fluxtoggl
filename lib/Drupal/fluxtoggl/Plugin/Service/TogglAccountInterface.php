<?php

/**
 * @file
 * Contains Drupal\fluxtoggl\Plugin\Service\TogglAccountInterface.
 */

namespace Drupal\fluxtoggl\Plugin\Service;

use Drupal\fluxservice\Plugin\Entity\AccountInterface;

/**
 * Interface TogglAccountInterface
 */
interface TogglAccountInterface extends AccountInterface {

  /**
   * Gets the Toggl client object.
   *
   * @return \AJT\Toggl\TogglClient
   *   The web service client for the Toggl API.
   */
  public function client();
}

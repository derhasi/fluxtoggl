<?php

/**
 * @file
 * Contains Drupal\fluxtoggl\Plugin\Service\TogglServiceInterface.
 */

namespace Drupal\fluxtoggl\Plugin\Service;

use Drupal\fluxservice\Plugin\Entity\ServiceInterface;

/**
 * Interface TogglServiceInterface
 */
interface TogglServiceInterface extends ServiceInterface {

  /**
   * Gets the update interval.
   *
   * @return int
   *   The update interval.
   */
  public function getPollingInterval();

  /**
   * Sets the update interval.
   *
   * @param int $interval
   *   The update interval.
   *
   * @return TwitterServiceInterface
   *   The called object for chaining.
   */
  public function setPollingInterval($interval);
}

<?php

/**
 * @file
 * Contains Drupal\fluxtoggl\TimeEntryController.
 */

namespace Drupal\fluxtoggl;

use Drupal\fluxservice\Plugin\Entity\AccountInterface;
use Drupal\fluxservice\Plugin\Entity\ServiceInterface;
use Drupal\fluxservice\Entity\RemoteEntityControllerByAccount;

/**
 * Entity controller for Twitter tweets.
 */
class TimeEntryController extends RemoteEntityControllerByAccount {

  /**
   * Loads remote items via the remote service.
   *
   * @param array $ids
   *   An array of remote ids.
   * @param \Drupal\fluxservice\Plugin\Entity\ServiceInterface $service
   *   The service endpoint used to load the entities.
   * @param \Drupal\fluxservice\Plugin\Entity\AccountInterface $account
   *   The service account used to load the entities.
   *
   * @return array
   *   An array of loaded items, keyed by remote id. It's safe to include
   *   additional, i.e. not requested items, to bycatch them for later.
   *   Not (more) existing entries should have the value FALSE.
   *
   * @throws \Exception
   *   For any connection problems.
   */
  protected function loadFromService($ids, ServiceInterface $service, AccountInterface $account) {

    if (!is_subclass_of($account, 'Drupal\fluxtoggl\Plugin\Service\TogglAccountInterface')) {
      throw new \Exception("TimeEntryController only works with TogglAccountInterface.");
    }

    $output = array();
    $client = $account->client();
    foreach ($ids as $id) {
      // We need to cast to (int) because of the strict type validation
      // implemented by Guzzle.
      if ($response = $client->getTimeEntry(array('id' => (int) $id))) {
        $output[$id] = $response;
      }
    }
    return $output;
  }

}

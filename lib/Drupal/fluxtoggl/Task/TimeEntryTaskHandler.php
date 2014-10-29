<?php

/**
 * @file
 * Contains TimelineTask.
 */

namespace Drupal\fluxtoggl\Task;
use Drupal\fluxservice\Rules\TaskHandler\RepetitiveTaskHandlerBase;

/**
 * Event dispatcher for the Twitter home timeline of a given user.
 */
class TimeEntryTaskHandler extends RepetitiveTaskHandlerBase {

  /**
   * {@inheritdoc}
   */
  public function runTask() {

    $identifier = $this->task['identifier'];
    $store = fluxservice_key_value('fluxtoggl.time-entries.at');

    $last_at = $store->get($identifier);

    $now = new \DateTime();
    $next_at = $now->format('c');

    $arguments = [];
    if (!empty($last_at)) {
      $arguments['at'] = $last_at;
    }

    $client = $this->getAccount()->client();
    $entries = $client->GetTimeEntries($arguments);

    foreach ($entries as $entry) {
      $this->invokeEvent($entry);
    }

    //$store->set($identifier, $next_at);

  }

  /**
   * Gets the configured Toggl account.
   *
   * @throws \RulesEvaluationException
   *   If the account cannot be loaded.
   *
   * @return \Drupal\fluxtoggl\Plugin\Service\TogglAccount
   *   The account associated with this task.
   */
  public function getAccount() {
    if (isset($this->account)) {
      return $this->account;
    }
    if (!$account = entity_load_single('fluxservice_account', $this->task['data']['account'])) {
      throw new \RulesEvaluationException('The specified Toggl account cannot be loaded.', array(), NULL, \RulesLog::ERROR);
    }
    $this->account = $account;
    return $this->account;
  }

//  /**
//   * {@inheritdoc}
//   */
//  public function afterTaskQueued() {
//    try {
//      $service = $this->getAccount()->getService();
//
//      // Continuously reschedule the task.
//      db_update('rules_scheduler')
//        ->condition('tid', $this->task['tid'])
//        ->fields(array('date' => $this->task['date'] + $service->getPollingInterval()))
//        ->execute();
//    }
//    catch(\RulesEvaluationException $e) {
//      rules_log($e->msg, $e->args, $e->severity);
//    }
//  }
}

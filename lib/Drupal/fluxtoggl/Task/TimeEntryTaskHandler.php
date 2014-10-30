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
    $store = fluxservice_key_value('fluxtoggl.time-entries.start_date');

    $last_start = $store->get($identifier);

    $arguments = [];
    if (!empty($last_start)) {
      $arguments['start_date'] = $last_start;
    }
    $next_start = $last_start;

    $account = $this->getAccount();
    $client = $account->client();
    $entries = $client->GetTimeEntries($arguments);

    foreach ($entries as $entry) {

      // If the time entry has no end date, this is the current one and we skip
      // processing, so we can grab that entry, when it is finished.
      if (empty($entry['stop'])) {
        break;
      }
      // As the API only processes a limit of 1000 entries per request, we have
      // to get the last start date from the actual entries.
      elseif ($entry['start'] > $next_start) {
        $next_start = $entry['start'];
      }

      $entity = fluxservice_entify($entry, 'fluxtoggl_time_entry', $account);

      rules_invoke_event($this->getEvent(), $account, $entity);
    }

    $store->set($identifier, $next_start);
  }

  /**
   * Gets the configured event name to dispatch.
   */
  public function getEvent() {
    return $this->task['identifier'];
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

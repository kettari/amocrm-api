<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 26.07.2016
 * Time: 13:33
 */

namespace AmoCrm\Api;


use AmoCrm\Api\Object\AmoTaskObject;

/**
 * Class AmoTaskController
 *
 * @package AmoCrm\Api
 */
class AmoTaskController extends AmoEntityController {

  /**
   * Create multiple tasks in the AmoCRM
   *
   * @param array $tasks
   * @return $this
   */
  public function addMultiple(array $tasks) {
    // Prepare data for request
    $data = [];
    /** @var AmoTaskObject $task */
    foreach ($tasks as $task) {
      if (!is_null($task->getId())) {
        $data['request']['tasks']['update'][] = $task->getArray();
      }
      else {
        $data['request']['tasks']['add'][] = $task->getArray();
      }
    }

    $this->buffer = array_merge($this->buffer, $data);

    // Add debug log message
    $this->logger->debug(sprintf('Tasks addMultiple(): <pre>%s</pre>',
      print_r($this->buffer, TRUE)), ['source' => __CLASS__ . '->' . __FUNCTION__]);

    return $this;
  }

  /**
   * @inheritdoc
   */
  public function execute() {
    return parent::_execute('tasks');
  }

  /**
   * Load all tasks from the amoCRM
   *
   * @return mixed
   */
  public function rosterAll() {
    $start = microtime(TRUE);
    $limit_rows = 500;
    $limit_offset = 0;
    $tasks_storage = [];

    // Download tasks until all done
    do {
      $response = $this->roster($limit_rows, $limit_offset);
      // Check for error
      if ($response->isErrorFlag()) {
        // Add log message
        $this->logger->error(sprintf('AmoCRM returned error. Message: %s',
          $response->getErrorMessage()), ['source' => __CLASS__ . '->' . __FUNCTION__]);

        return FALSE;
      }
      else {
        $payload = $response->getPayload();
        if (is_array($payload) && (count($payload) > 0)) {
          // Add contacts to the storage
          $tasks_storage = array_merge($tasks_storage, $payload);
          $limit_offset += count($payload);
        }
      }
    } while (count($payload) == $limit_rows);

    // Add log message
    $time_elapsed_secs = microtime(TRUE) - $start;
    $this->logger->info(sprintf('Roster all tasks from amoCRM execution time: %.2f seconds',
      $time_elapsed_secs), ['source' => __CLASS__ . '->' . __FUNCTION__]);

    return $tasks_storage;
  }

  /**
   * Load list of tasks
   *
   * @param int $limit_rows
   * @param int $limit_offset
   * @return \AmoCrm\Api\Response\AmoBaseResponse
   */
  public function roster($limit_rows = 500, $limit_offset = 0) {
    return parent::_find('tasks', $limit_rows, $limit_offset);
  }

  /**
   * Load all tasks for specified lead
   *
   * @param $lead_id
   * @return array|bool
   */
  public function rosterForLead($lead_id) {
    // Set the limits
    $limit_rows = 500;
    $limit_offset = 0;

    // Prepare the link
    $link = $this->getBaseLink() . sprintf('/private/api/v2/json/%s/list',
        'tasks');
    // Add auth
    $link = $link . sprintf('?USER_LOGIN=%s&USER_HASH=%s',
        $this->user_login, $this->api_hash);
    // Add parameters
    $link .= sprintf('&limit_rows=%d', $limit_rows);
    $link .= sprintf('&element_id=%d', $lead_id);

    // Download records until all done
    $data = [];
    do {
      $offset_link = $link . sprintf('&limit_offset=%d', $limit_offset);
      $response = $this->sendRequest('GET', $offset_link);
      // Check for error
      if ($response->isErrorFlag()) {
        // Add log message
        $this->logger->error(sprintf('AmoCRM returned error. Message: %s',
          $response->getErrorMessage()), ['source' => __CLASS__ . '->' . __FUNCTION__]);

        return FALSE;
      }
      else {
        $payload = $response->getPayload();
        if (is_array($payload) && (count($payload) > 0)) {
          // Add records to the storage
          $data = array_merge($data, $payload);
          $limit_offset += count($payload);
        }
      }
    } while (count($payload) == $limit_rows);

    return $data;
  }
}
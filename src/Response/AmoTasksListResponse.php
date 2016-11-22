<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 29.07.2016
 * Time: 16:43
 */

namespace AmoCrm\Api\Response;


use AmoCrm\Api\Objects\AmoTaskObject;

class AmoTasksListResponse extends AmoBaseResponse {

  /**
   * AmoTasksListResponse constructor.
   *
   * @param integer $http_code
   * @param mixed $raw_result
   */
  public function __construct($http_code, $raw_result) {
    parent::__construct($http_code, $raw_result);

    // Check for empty response
    if (204 == $http_code) {
      $this->payload = [];
      return;
    }

    // Customization goes here
    if (!$this->isErrorFlag()) {
      // Iterate and create objects
      if (isset($this->payload['tasks']) && is_array($this->payload['tasks'])) {
        $objects = [];
        foreach ($this->payload['tasks'] as $task) {
          $objects[] = new AmoTaskObject($task);
        }
        // Substitute generic array with array of objects
        $this->payload = $objects;
      }
    }
  }
}
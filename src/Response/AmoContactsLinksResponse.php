<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 29.07.2016
 * Time: 16:43
 */

namespace AmoCrm\Api\Response;


use AmoCrm\Api\Objects\AmoLinkObject;

class AmoContactsLinksResponse extends AmoBaseResponse {

  /**
   * AmoContactsListResponse constructor.
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
      if (isset($this->payload['links']) && is_array($this->payload['links'])) {
        $objects = [];
        foreach ($this->payload['links'] as $link) {
          $objects[] = new AmoLinkObject($link);
        }
        // Substitute generic array with array of objects
        $this->payload = $objects;
      }
    }
  }
}
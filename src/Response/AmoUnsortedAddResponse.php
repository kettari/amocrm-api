<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 29.07.2016
 * Time: 16:43
 */

namespace AmoCrm\Api\Response;



class AmoUnsortedAddResponse extends AmoBaseResponse {

  /**
   * AmoContactsListResponse constructor.
   *
   * @param integer $http_code
   * @param mixed $raw_result
   */
  public function __construct($http_code, $raw_result) {
    parent::__construct($http_code, $raw_result);

    // Customization goes here
    if ($this->isErrorFlag()) {

      // Check response fields
      if (isset($this->payload['unsorted']['add']['error_code'])
        && isset($this->payload['unsorted']['add']['error'])
      ) {

        // Overwrite the error with extended info
        $this->error_message = sprintf('API error when tried to add unsorted data: %s, code=%d',
          $this->payload['unsorted']['add']['error'],
          $this->payload['unsorted']['add']['error_code']
        );

      }

    }

  }
}
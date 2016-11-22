<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 29.07.2016
 * Time: 16:43
 */

namespace AmoCrm\Api\Response;


class AmoEntitySetResponse extends AmoBaseResponse {

  /**
   * Name of entity (contacts, leads)
   */
  protected $entity = 'entity';

  /**
   * Operation: add/update
   *
   * @var array
   */
  protected $op = [];

  /**
   * AmoEntitySetResponse constructor.
   *
   * @param integer $http_code
   * @param mixed $raw_result
   */
  public function __construct($http_code, $raw_result) {
    parent::__construct($http_code, $raw_result);

    // Select operation
    if (isset($this->payload[$this->entity]['add'])) {
      $this->op['add'] = TRUE;
    }
    if (isset($this->payload[$this->entity]['update'])) {
      $this->op['update'] = TRUE;
    }

    // Customization goes here
    if ($this->isErrorFlag()) {
      $this->error_message = 'API error';
      foreach ($this->op as $operation) {
        // Check response fields
        if (isset($this->payload[$this->entity][$operation]['error_code'])
          && isset($this->payload[$this->entity][$operation]['error'])
        ) {

          // Overwrite the error with extended info
          $this->error_message .= sprintf(' when tried to %s %s: %s, code=%d' . PHP_EOL,
            $operation,
            $this->entity,
            $this->payload[$this->entity][$this->op]['error'],
            $this->payload[$this->entity][$this->op]['error_code']
          );

        }
        else {
          // Overwrite the error with extended info
          $this->error_message .= sprintf(' with %s: %s, code=%d' . PHP_EOL,
            $this->entity,
            $this->payload['error'],
            $this->payload['error_code']
          );
        }
      }

    }

  }
}
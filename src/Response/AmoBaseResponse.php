<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 26.07.2016
 * Time: 14:17
 */

namespace AmoCrm\Api\Response;


class AmoBaseResponse {

  /**
   * Payload of response
   *
   * @var array
   */
  protected $payload = [];

  /**
   * Error flag
   *
   * @var bool
   */
  protected $error_flag = FALSE;

  /**
   * Descriptive error message
   *
   * @var string
   */
  protected $error_message;

  /**
   * Error code
   *
   * @var integer
   */
  protected $error_code;

  /**
   * Server timestamp
   *
   * @var integer
   */
  protected $server_time = 0;

  /**
   * AmoResponse constructor.
   *
   * @param integer $http_code
   * @param mixed $raw_result
   */
  public function __construct($http_code, $raw_result) {
    if (!is_null($raw_result)) {
      $raw_array = json_decode($raw_result, TRUE);
      if (is_array($raw_array)) {
        // Check if there is 'response' element
        if (isset($raw_array['response'])) {
          // Copy 'response' to payload property
          $this->payload = $raw_array['response'];
        }
        // Store server timestamp
        if (isset($this->payload['server_time'])) {
          $this->server_time = $this->payload['server_time'];
        }
        else {
          $this->server_time = 0;
        }
      }
    }

    // Check HTTP code
    if (!((200 == $http_code) || (201 == $http_code) || (204 == $http_code))) {
      $this->error_flag = TRUE;
      $this->error_code = $http_code;

      // Describe HTTP error
      switch ($http_code) {
        case 403:
          $this->error_message = sprintf('Forbidden, HTTP code=%d', $http_code);
          break;
        case 404:
          $this->error_message = sprintf('Not found, HTTP code=%d', $http_code);
          break;
        case 429:
          $this->error_message = sprintf('API request limit exceeded, HTTP code=%d', $http_code);
          break;
        default:
          $this->error_message = sprintf('HTTP error, code=%d', $http_code);
          break;
      }
      return;
    }

    // If HTTP code is 204, nothing found/no content
    if (204 == $http_code) {
      return;
    }

  }

  /**
   * Return payload as array
   *
   * @return array
   */
  public function getPayload() {
    return $this->payload;
  }

  /**
   * Set payload
   *
   * @param $payload
   */
  public function setPayload($payload) {
    $this->payload = $payload;
  }

  /**
   * @return boolean
   */
  public function isErrorFlag() {
    return $this->error_flag;
  }

  /**
   * @return string
   */
  public function getErrorMessage() {
    return $this->error_message;
  }

  /**
   * @return int
   */
  public function getErrorCode() {
    return $this->error_code;
  }

  /**
   * @return int
   */
  public function getServerTime() {
    return $this->server_time;
  }

}
<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 26.07.2016
 * Time: 13:37
 */

namespace AmoCrm\Api;

use AmoCrm\Api\Response\AmoBaseResponse;
use AmoCrm\Api\Response\AmoResponseFactory;

/**
 * Class AmoBaseController
 *
 * @package AmoCrm\Api
 */
class AmoBaseController extends AmoLoggable {

  /**
   * Поддомен amoCRM для вашей компании.
   *
   * @var string
   */
  protected $subdomain;

  /**
   * Логин пользователя. В качестве логина в системе используется e-mail.
   *
   * @var string
   */
  protected $user_login;

  /**
   * Ключ пользователя, который можно получить на странице редактирования
   * профиля пользователя.
   *
   * @var string
   */
  protected $api_hash;

  /**
   * Last raw response from the AmoCRM server
   *
   * @var mixed
   */
  protected $last_raw_result;

  /**
   * @var AmoBurstController
   */
  protected $burst_controller;

  /**
   * AmoBaseController constructor.
   *
   * @param string $logentries_token
   * @param string $amo_subdomain
   * @param string $amo_user_login
   * @param string $amo_api_hash
   */
  public function __construct($logentries_token, $amo_subdomain, $amo_user_login,
                              $amo_api_hash) {
    parent::__construct($logentries_token);

    $this->subdomain = $amo_subdomain;
    $this->user_login = $amo_user_login;
    $this->api_hash = $amo_api_hash;

    $this->burst_controller = AmoBurstController::getInstance($logentries_token);
  }

  /**
   * Return base URI: protocol://subdomain.amocrm.ru
   *
   * @return string
   */
  protected function getBaseLink() {
    return sprintf('https://%s.amocrm.ru', $this->subdomain);
  }

  /**
   * @param string $method GET or POST
   * @param string $link Url to call
   * @param array $data Array of params
   * @return AmoBaseResponse
   */
  protected function sendRequest($method, $link, $data = NULL) {
    // Prepare var for the log
    $message_data = (!is_null($data) ? print_r($data, TRUE) : 'NULL');

    // Burst control
    if (!$this->burst_controller->wait()) {

      // Add debug log message
      $this->logger->debug(sprintf('Burst control denied AmoCRM API request: method=%s, link=%s. Data: <pre>%s</pre>',
        $method,
        $link,
        $message_data
      ), ['source' => __CLASS__ . '->' . __FUNCTION__]);

      return new AmoBaseResponse(500, NULL);
    }

    // Go on with cURL
    $curl = curl_init();
    // Set up request options
    curl_setopt($curl, CURLOPT_URL, $link);
    $this->setCurlOptions($curl);
    // Check method and presence of data
    if ('post' == strtolower($method) && !is_null($data)) {
      curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    }

    // Send request
    $this->last_raw_result = curl_exec($curl);
    $this->burst_controller->requestSent();
    // Get HTTP code
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl); // Finalize request

    // Try to decode JSON
    $decoded_result = json_decode($this->last_raw_result, TRUE);
    if (json_last_error() != JSON_ERROR_NONE) {
      $decoded_result = $this->last_raw_result;
    }
    $decoded_result = print_r($decoded_result, TRUE);

    // Add debug log message
    $this->logger->debug(sprintf('Sent AmoCRM API request at %.2f: method=%s, link=%s. Data: <pre>%s</pre>'
      . '<br />Response: HTTP code=%s, payload:<pre>%s</pre>',
      microtime(TRUE),
      $method,
      $link,
      $message_data,
      $http_code,
      $decoded_result
    ), ['source' => __CLASS__ . '->' . __FUNCTION__]);

    // Build implementation of AmoBaseResponse or one of it's ancestors
    $amo_response = AmoResponseFactory::build($link, $http_code, $this->last_raw_result);
    if ($amo_response->isErrorFlag()) {
      // Add error log message
      $this->logger->error(sprintf('AmoCRM API request returned error: %s. '
        . 'Details follow: method=%s, link=%s. Data: <pre>%s</pre>'
        . '<br />Response: HTTP code=%s, payload:<pre>%s</pre>',
        $amo_response->getErrorMessage(),
        $method,
        $link,
        $message_data,
        $http_code,
        $decoded_result), ['source' => __CLASS__ . '->' . __FUNCTION__]);
    }

    return $amo_response;
  }

  /**
   * Set curl options
   *
   * @param $handler
   */
  protected function setCurlOptions(&$handler) {
    curl_setopt($handler, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($handler, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
    curl_setopt($handler, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    curl_setopt($handler, CURLOPT_HEADER, FALSE);
    curl_setopt($handler, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($handler, CURLOPT_SSL_VERIFYHOST, 0);
  }

}
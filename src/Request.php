<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 18.01.2017
 * Time: 18:34
 */

namespace AmoCrm\Client;


use AmoCrm\Client\Exception\JsonDecodeException;
use Monolog\Logger;

class Request {

  /**
   * @var Logger
   */
  protected $logger;

  /**
   * @var resource
   */
  protected $handler;

  /**
   * @var string
   */
  protected $url;

  /**
   * @var string
   */
  protected $method;

  /**
   * @var string
   */
  protected $login;

  /**
   * @var string
   */
  protected $api_hash;

  /**
   * @var string
   */
  protected $query;


  /**
   * Api constructor.
   *
   * @param \Monolog\Logger $logger
   */
  public function __construct(Logger $logger) {
    $this->logger = $logger;
  }

  /**
   * Send HTTP GET request to amoCRM server and return response
   *
   * @return mixed|null
   */
  public function get() {
    return $this->execute('GET');
  }

  /**
   * Execute cURL
   *
   * @param string $http_method HTTP method 'GET' or 'POST'
   * @param array $params
   * @return mixed|null
   * @throws \Exception
   * @throws \HttpResponseException
   */
  protected function execute($http_method, $params = []) {
    $result = NULL;
    $handler = $this->getHandler();

    if ('post' == strtolower($http_method)) {
      curl_setopt($handler, CURLOPT_POSTFIELDS, http_build_query($params));
    }
    $curl_result = curl_exec($handler);
    $http_code = curl_getinfo($handler, CURLINFO_HTTP_CODE);

    // Add log
    $this->logger->info('amoCRM server returned HTTP code {http_code} for URI "{uri}"', [
      'http_code' => $http_code,
      'uri'       => $this->getUri(),
    ]);
    $this->logger->debug('amoCRM request and response details', [
      'post_fields' => ('post' == $http_method) ? substr(print_r($params, TRUE), 0, 512) : NULL,
      'response'    => substr(print_r($curl_result, TRUE), 0, 512),
    ]);

    // Check HTTP code
    if ((200 != $http_code) && (204 != $http_code)) {
      throw new \HttpResponseException(sprintf('amoCRM server returned HTTP code %d for URI "%s"',
        $http_code, $this->getUri()));
    }
    elseif (200 == $http_code) {
      // Try to decode JSON
      $result = json_decode($curl_result, TRUE);
      if (json_last_error() != JSON_ERROR_NONE) {
        throw new JsonDecodeException('Error decoding JSON');
      }
    }

    $this->closeHandler();
    return $result;
  }

  /**
   * Get cURL handler
   */
  public function getHandler() {
    if (!is_null($this->handler)) {
      return $this->handler;
    }

    $uri = $this->getUri();

    // Set up request options
    $this->handler = curl_init();
    curl_setopt($this->handler, CURLOPT_URL, $uri);
    curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($this->handler, CURLOPT_USERAGENT, 'kettari-amocrm-api-client/1.0');
    curl_setopt($this->handler, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    curl_setopt($this->handler, CURLOPT_HEADER, FALSE);
    curl_setopt($this->handler, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($this->handler, CURLOPT_SSL_VERIFYHOST, 0);

    $this->logger->debug(sprintf('Created cURL handler with uri "{uri}"', ['uri' => $uri]));

    return $this->handler;
  }

  /**
   * Get fully qualified URI for request
   *
   * @return string
   */
  public function getUri() {
    $uri = sprintf('%s%s?USER_LOGIN=%s&USER_HASH=%s',
      $this->getUrl(), $this->getMethod(), $this->getLogin(), $this->getApiHash());

    // If query defined, concatenate it
    if (!empty($this->query)) {
      $uri .= sprintf('&query=%s', urlencode($this->query));
    }

    return $uri;
  }

  /**
   * Get URL, for example 'http://subdomain.amocrm.ru'
   *
   * @return string
   */
  public function getUrl() {
    return $this->url;
  }

  /**
   * Set URL, for example 'http://subdomain.amocrm.ru'
   *
   * @param string $url
   * @return Request
   */
  public function setUrl($url) {
    $this->url = $url;
    return $this;
  }

  /**
   * Get API method, for example '/private/api/v2/json/contacts/list'
   *
   * @return string
   */
  public function getMethod() {
    return $this->method;
  }

  /**
   * Set API method, for example '/private/api/v2/json/contacts/list'
   *
   * @param string $method
   * @return Request
   */
  public function setMethod($method) {
    $this->method = $method;
    return $this;
  }

  /**
   * Get user login
   *
   * @return string
   */
  public function getLogin() {
    return $this->login;
  }

  /**
   * Set user login
   *
   * @param string $login
   * @return Request
   */
  public function setLogin($login) {
    $this->login = $login;
    return $this;
  }

  /**
   * Get API hash
   *
   * @return string
   */
  public function getApiHash() {
    return $this->api_hash;
  }

  /**
   * Set API hash
   *
   * @param string $api_hash
   * @return Request
   */
  public function setApiHash($api_hash) {
    $this->api_hash = $api_hash;
    return $this;
  }

  /**
   * Close handler
   */
  public function closeHandler() {
    curl_close($this->handler);
    $this->handler = NULL;

    $this->logger->debug('cURL handler closed');
  }

  /**
   * Send HTTP POST request to amoCRM server and return response
   *
   * @param array $params
   * @return mixed|null
   */
  public function post($params) {
    return $this->execute('POST', $params);
  }

  /**
   * @return string
   */
  public function getQuery() {
    return $this->query;
  }

  /**
   * @param string $query
   * @return $this
   */
  public function setQuery($query) {
    $this->query = $query;
    return $this;
  }


}
<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 26.07.2016
 * Time: 13:33
 */

namespace AmoCrm\Api;

use AmoCrm\Api\Response\AmoBaseResponse;

/**
 * Class AmoEntityController
 *
 * @package AmoCrm\Api
 */
class AmoEntityController extends AmoBaseController {

  /**
   * Buffer for the entities before they are flushed to the amoCRM
   *
   * @var array
   */
  protected $buffer = [];

  /**
   * Max number of elements to send within the request
   *
   * @var int
   */
  protected $request_max_elements = 100;

  /**
   * Filter query string
   *
   * @var string
   */
  protected $filter_query = NULL;

  /**
   * Filter responsible user id
   *
   * @var string
   */
  protected $filter_responsible_user_id = NULL;

  /**
   * IDs of entities
   *
   * @var array
   */
  protected $filter_ids = [];

  /**
   * Clear internal buffer
   */
  public function clearBuffer() {
    $this->buffer = [];
    return $this;
  }

  /**
   * Returns length of the internal buffer
   *
   * @return int
   */
  public function getBufferLength() {
    return count($this->buffer);
  }

  /**
   * @return string
   */
  public function getFilterQuery() {
    return $this->filter_query;
  }

  /**
   * @param string $filter_query
   * @return AmoEntityController
   */
  public function setFilterQuery($filter_query) {
    $this->filter_query = $filter_query;
    return $this;
  }

  /**
   * @return string
   */
  public function getFilterResponsibleUserId() {
    return $this->filter_responsible_user_id;
  }

  /**
   * @param string $filter_responsible_user_id
   * @return AmoEntityController
   */
  public function setFilterResponsibleUserId($filter_responsible_user_id) {
    $this->filter_responsible_user_id = $filter_responsible_user_id;
    return $this;
  }

  /**
   * @return array
   */
  public function getFilterIds() {
    return $this->filter_ids;
  }

  /**
   * @param array $filter_ids
   * @return $this
   */
  public function setFilterIds($filter_ids) {
    $this->filter_ids = $filter_ids;
    return $this;
  }

  /**
   * Alias for find() method.
   *
   * @param string $entity
   * @param string $query
   * @return bool
   */
  protected function _exists($entity, $query) {
    // Execute search
    $this->setFilterQuery($query);
    $amo_response = $this->_find($entity, 1);
    if ($amo_response->isErrorFlag()) {
      return FALSE;
    }
    else {
      // Request successful, check results count
      return (count($amo_response->getPayload()) > 0);
    }
  }

  /**
   * Find entities in the AmoCRM. Filtered with query substring.
   *
   * @param string $entity
   * @param int $limit_rows
   * @param int $limit_offset
   * @return \AmoCrm\Api\Response\AmoBaseResponse
   */
  protected function _find($entity, $limit_rows = 50, $limit_offset = 0) {
    // Prepare the link
    $link = $this->getBaseLink() . sprintf('/private/api/v2/json/%s/list',
        $entity);
    // Add auth
    $link = $link . sprintf('?USER_LOGIN=%s&USER_HASH=%s',
        $this->user_login, $this->api_hash);
    // Add parameters
    $link .= sprintf('&limit_rows=%d', $limit_rows);
    if (!empty($this->filter_query)) {
      $link .= sprintf('&query=%s', urlencode($this->filter_query));
    }
    if (!is_null($this->filter_responsible_user_id)) {
      $link .= sprintf('&responsible_user_id=%d', $this->filter_responsible_user_id);
    }
    if (0 != $limit_offset) {
      $link .= sprintf('&limit_offset=%d', $limit_offset);
    }
    foreach ($this->filter_ids as $id) {
      $link .= sprintf('&id[]=%d', $id);
    }

    // Send request to AmoCRM API
    return $this->sendRequest('GET', $link);
  }

  /**
   * Create entity in the AmoCRM
   *
   * @param string $entity
   * @param array $data
   * @return \AmoCrm\Api\Response\AmoBaseResponse
   */
  /*protected function save($entity, $data) {
    // Prepare link
    $link = $this->getBaseLink() . sprintf('/private/api/v2/json/%s/set', $entity);
    // Add auth
    $link = $link . sprintf('?USER_LOGIN=%s&USER_HASH=%s',
        $this->user_login, $this->api_hash);

    // Send request to AmoCRM API
    return $this->sendRequest('POST', $link, $data);
  }*/

  /**
   * Create/update entities from the internal buffer in the AmoCRM
   *
   * @param string $entity
   * @return \AmoCrm\Api\Response\AmoBaseResponse
   */
  protected function _execute($entity) {
    // Prepare link
    $link = $this->getBaseLink() . sprintf('/private/api/v2/json/%s/set', $entity);
    // Add auth
    $link = $link . sprintf('?USER_LOGIN=%s&USER_HASH=%s',
        $this->user_login, $this->api_hash);

    // Create pagination
    $operations_list = ['add', 'update'];
    /** @var AmoBaseResponse $total_result */
    $total_result = NULL;
    foreach ($operations_list as $operation) {
      if (isset($this->buffer['request'][$entity][$operation])) {
        $buffer = $this->buffer['request'][$entity][$operation];
        do {
          // Send chunk
          $chunk = array_splice($buffer, 0, $this->request_max_elements);
          $partial_request = [
            'request' => [
              $entity => [
                $operation => $chunk,
              ],
            ],
          ];

          // Send request to AmoCRM API
          $chunk_result = $this->sendRequest('POST', $link, $partial_request);
          if ($chunk_result->isErrorFlag()) {
            return $chunk_result;
          }
          if (is_null($total_result)) {
            // Initial result
            $total_result = $chunk_result;
          }
          else {
            // Subsequent result
            $payload = array_merge($total_result->getPayload(), $chunk_result->getPayload());
            $total_result->setPayload($payload);
          }
        }
        while (count($buffer) > 0);
      }
    }

    return $total_result;
  }
}
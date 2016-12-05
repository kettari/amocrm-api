<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 16.08.2016
 * Time: 16:39
 */

namespace AmoCrm\Api;

use AmoCrm\Api\Object\AmoPipelineObject;
use AmoCrm\Api\Object\AmoUserObject;


/**
 * Class AmoAccountController
 *
 * @package AmoCrm\Api
 */
class AmoAccountController extends AmoBaseController {

  /**
   * Pipeline IDs
   */
  /*const PIPELINE_ID_NEW_1to5 = 211572;
  const PIPELINE_ID_NEW_6to21 = 211596;
  const PIPELINE_ID_NEW_22to45 = 211602;
  const PIPELINE_ID_SINGLE_PASS = 229263;
  const PIPELINE_ID_ENTRANCE = 224377;
  const PIPELINE_ID_LONG = 229272;*/

  /**
   * Open statuses for the pipeline "NEW 1 to 5 DAYS"
   */
  /*const STATUS_NEW_1to5_1stCONTACT_ID = 11353344;
  const STATUS_NEW_1to5_QUALIFIED = 11353347;
  const STATUS_NEW_1to5_MEETING_PLANNED = 11353350;
  const STATUS_NEW_1to5_MEETING_VISITED = 11434354;
  const STATUS_NEW_1to5_PUSH = 11434543;*/

  /**
   * Open statuses for the pipeline "NEW 6 to 22 DAYS"
   */
  /*const STATUS_NEW_6to21_FROM_1to5_DAYS = 11353494;
  const STATUS_NEW_6to21_MEETING_PLANNED = 11353500;
  const STATUS_NEW_6to21_MEETING_VISITED = 11353497;
  const STATUS_NEW_6to21_PUSH = 11518608;*/

  /**
   * Open statuses for the pipeline "NEW 23 to 45 DAYS"
   */
  /*const STATUS_NEW_23to45_FROM_6to21_DAYS = 11353533;
  const STATUS_NEW_23to45_MEETING_PLANNED = 11353536;
  const STATUS_NEW_23to45_MEETING_VISITED = 11353539;
  const STATUS_NEW_23to45_PUSH = 11518614;*/

  /**
   * Cached result of account request
   *
   * @var mixed
   */
  protected $cached_result = NULL;

  /**
   * Get array of AmoUserObject
   *
   * @return array
   */
  public function getUsers() {
    $account_response = $this->get();
    if (!$account_response->isErrorFlag()) {
      $payload = $account_response->getPayload();
      if (isset($payload['account']['users']) && is_array($payload['account']['users'])) {
        $result = [];
        foreach ($payload['account']['users'] as $item) {
          $result[] = new AmoUserObject($item);
        }
        return $result;
      }
    }
    return NULL;
  }

  /**
   * Get array of AmoPipelineObject
   *
   * @return array
   */
  public function getPipelines() {
    $account_response = $this->get();
    if (!$account_response->isErrorFlag()) {
      $payload = $account_response->getPayload();
      if (isset($payload['account']['pipelines']) && is_array($payload['account']['pipelines'])) {
        $result = [];
        foreach ($payload['account']['pipelines'] as $id => $item) {
          $result[$id] = new AmoPipelineObject($item);
        }
        return $result;
      }
    }
    return NULL;
  }

  /**
   * Get account information
   *
   * @return \AmoCrm\Api\Response\AmoBaseResponse
   */
  public function get() {
    if (!is_null($this->cached_result)) {
      return $this->cached_result;
    }

    // Prepare the link
    $link = $this->getBaseLink() . '/private/api/v2/json/accounts/current';
    // Add auth
    $link = $link . sprintf('?USER_LOGIN=%s&USER_HASH=%s',
        $this->user_login, $this->api_hash);

    // Send request to AmoCRM API
    $this->cached_result = $this->sendRequest('GET', $link);
    return $this->cached_result;
  }

  /**
   * Clear cached result
   */
  public function clearCache() {
    $this->cached_result = NULL;
  }

}
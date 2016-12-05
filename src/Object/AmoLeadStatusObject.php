<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 14.09.2016
 * Time: 18:27
 */

namespace AmoCrm\Api\Object;


/**
 * Class AmoLeadStatusObject
 *
 * @package AmoCrm\Api\Object
 */
class AmoLeadStatusObject {
  /**
   * @var string
   */
  protected $operation;

  /**
   * @var integer
   */
  protected $id;

  /**
   * @var integer
   */
  protected $current_status_id;

  /**
   * @var integer
   */
  protected $current_pipeline_id;

  /**
   * @var integer
   */
  protected $old_status_id;

  /**
   * @var integer
   */
  protected $old_pipeline_id;

  /**
   * @var integer
   */
  protected $account_id;

  /**
   * Construct AmoLeadStatusObject
   *
   *
   * New:
   * leads[add][0][status_id]: 11353344
   * leads[add][0][pipeline_id]: 211572
   * leads[add][0][id]: 12893663
   * account[subdomain]: kaula
   * account[id]: 9718704
   *
   * Update:
   * leads[status][0][id]: 12893357
   * account[subdomain]: kaula
   * leads[status][0][old_pipeline_id]: 211572
   * leads[status][0][old_status_id]: 11353344
   * account[id]: 9718704
   * leads[status][0][pipeline_id]: 211572
   * leads[status][0][status_id]: 11353347
   *
   * @param array $data
   */
  public function __construct($data) {
    if (isset($data['account']['id'])) {
      $this->account_id = $data['account']['id'];
    }
    if (isset($data['leads']['add'])) {
      $this->operation = 'add';
    }
    elseif (isset($data['leads']['status'])) {
      $this->operation = 'status';
    }
    else {
      $this->operation = 'unknown';
    }
    if (
      is_array($data['leads'][$this->operation]) &&
      (count($data['leads'][$this->operation]) > 0) &&
      ($lead = reset($data['leads'][$this->operation]))
    ) {
      $this->id = $lead['id'];
      $this->current_pipeline_id = $lead['pipeline_id'];
      $this->current_status_id = $lead['status_id'];
      if ('status' == $this->operation) {
        $this->old_pipeline_id = $lead['old_pipeline_id'];
        $this->old_status_id = $lead['old_status_id'];
      }
    }
  }

  /**
   * @return string
   */
  public function getOperation() {
    return $this->operation;
  }

  /**
   * @return int
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @return int
   */
  public function getCurrentStatusId() {
    return $this->current_status_id;
  }

  /**
   * @return int
   */
  public function getCurrentPipelineId() {
    return $this->current_pipeline_id;
  }

  /**
   * @return int
   */
  public function getOldStatusId() {
    return $this->old_status_id;
  }

  /**
   * @return int
   */
  public function getOldPipelineId() {
    return $this->old_pipeline_id;
  }

  /**
   * @return mixed
   */
  public function getAccountId() {
    return $this->account_id;
  }
}
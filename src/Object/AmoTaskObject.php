<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 26.07.2016
 * Time: 15:55
 */

namespace AmoCrm\Api\Object;


/**
 * Class AmoTaskObject
 *
 * @package AmoCrm\Api\Object
 */
class AmoTaskObject {

  /**
   * Task statuses
   */
  const TASK_STATUS_OPEN = 0;
  const TASK_STATUS_CLOSED = 1;

  /**
   * AmoCRM ID
   *
   * @var string
   */
  protected $id = NULL;

  /**
   * Lead or contact id
   *
   * @var integer
   */
  protected $element_id = NULL;

  /**
   * Type of the related element:
   *  1 - contact
   *  2 - lead
   *  3 - company
   *
   * @var integer
   */
  protected $element_type = NULL;

  /**
   * Unix timestamp
   *
   * @var integer
   */
  protected $date_create = NULL;

  /**
   * Task status:
   *  0 - open
   *  1 - close
   *
   * @var integer
   */
  protected $status = NULL;

  /**
   * Responsible user ID
   *
   * @var integer
   */
  protected $responsible_user_id = NULL;

  /**
   * Deadline
   *
   * @var integer
   */
  protected $complete_till = NULL;

  /**
   * Task type (from account settings)
   *
   * @var integer
   */
  protected $task_type = NULL;

  /**
   * Text of the task
   *
   * @var string
   */
  protected $text = NULL;

  /**
   * ID of the entity in the client system
   *
   * @var string
   */
  protected $request_id = NULL;

  /**
   * @var null
   */
  protected $_rel_entity_hash = NULL;

  /**
   * AmoLeadObject constructor.
   *
   * @param array $data
   */
  public function __construct(array $data) {
    // Element type set to lead (1=contact,2=lead,3=company)
    $this->element_type = 2;
    // Status = open
    $this->status = 0;
    // Task type; 1 - follow up or meet
    $this->task_type = 1;
    // Creation date - now
    $this->date_create = time();
    // Assign properties
    foreach ($data as $key => $val) {
      switch ($key) {
        default:
          if (property_exists(__CLASS__, $key)) {
            $this->$key = $val;
          }
          continue;
      }
    }
  }

  /**
   * @inheritDoc
   */
  /** @noinspection PhpToStringReturnInspection */
  function __toString() {
    $result = print_r($this, TRUE);
    return $result;
  }


  /**
   * Return array ready to be sent to AmoCRM
   *
   * @return array
   */
  public function getArray() {
    // Build result array
    $result = [
      'date_create'   => $this->date_create,
      'last_modified' => time(),
      'request_id'    => $this->request_id,
    ];

    if (!is_null($this->id)) {
      $result['id'] = $this->id;
    }
    if (!is_null($this->element_id)) {
      $result['element_id'] = $this->element_id;
    }
    if (!is_null($this->element_type)) {
      $result['element_type'] = $this->element_type;
    }
    if (!is_null($this->status)) {
      $result['status'] = $this->status;
    }
    if (!is_null($this->task_type)) {
      $result['task_type'] = $this->task_type;
    }
    if (!is_null($this->text)) {
      $result['text'] = $this->text;
    }
    if (!is_null($this->responsible_user_id)) {
      $result['responsible_user_id'] = $this->responsible_user_id;
    }
    if (!is_null($this->complete_till)) {
      $result['complete_till'] = $this->complete_till;
    }

    return $result;
  }

  /**
   * @return string
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @param string $id
   * @return AmoTaskObject
   */
  public function setId($id) {
    $this->id = $id;
    return $this;
  }

  /**
   * @return int
   */
  public function getElementId() {
    return $this->element_id;
  }

  /**
   * @param int $element_id
   * @return AmoTaskObject
   */
  public function setElementId($element_id) {
    $this->element_id = $element_id;
    return $this;
  }

  /**
   * @return int
   */
  public function getElementType() {
    return $this->element_type;
  }

  /**
   * @param int $element_type
   * @return AmoTaskObject
   */
  public function setElementType($element_type) {
    $this->element_type = $element_type;
    return $this;
  }

  /**
   * @return int
   */
  public function getDateCreate() {
    return $this->date_create;
  }

  /**
   * @param int $date_create
   * @return AmoTaskObject
   */
  public function setDateCreate($date_create) {
    $this->date_create = $date_create;
    return $this;
  }

  /**
   * @return int
   */
  public function getStatus() {
    return $this->status;
  }

  /**
   * @param int $status
   * @return AmoTaskObject
   */
  public function setStatus($status) {
    $this->status = $status;
    return $this;
  }

  /**
   * @return int
   */
  public function getResponsibleUserId() {
    return $this->responsible_user_id;
  }

  /**
   * @param int $responsible_user_id
   * @return AmoTaskObject
   */
  public function setResponsibleUserId($responsible_user_id) {
    $this->responsible_user_id = $responsible_user_id;
    return $this;
  }

  /**
   * @return int
   */
  public function getCompleteTill() {
    return $this->complete_till;
  }

  /**
   * @param int $complete_till
   * @return AmoTaskObject
   */
  public function setCompleteTill($complete_till) {
    $this->complete_till = $complete_till;
    return $this;
  }

  /**
   * @return int
   */
  public function getTaskType() {
    return $this->task_type;
  }

  /**
   * @param int $task_type
   * @return AmoTaskObject
   */
  public function setTaskType($task_type) {
    $this->task_type = $task_type;
    return $this;
  }

  /**
   * @return string
   */
  public function getText() {
    return $this->text;
  }

  /**
   * @param string $text
   * @return AmoTaskObject
   */
  public function setText($text) {
    $this->text = $text;
    return $this;
  }

  /**
   * @return string
   */
  public function getRequestId() {
    return $this->request_id;
  }

  /**
   * @param string $request_id
   * @return AmoTaskObject
   */
  public function setRequestId($request_id) {
    $this->request_id = $request_id;
    return $this;
  }

  /**
   * @return null
   */
  public function getRelEntityHash() {
    return $this->_rel_entity_hash;
  }

  /**
   * @param null $rel_entity_hash
   * @return AmoTaskObject
   */
  public function setRelEntityHash($rel_entity_hash) {
    $this->_rel_entity_hash = $rel_entity_hash;
    return $this;
  }
}
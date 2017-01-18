<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 19.01.2017
 * Time: 0:49
 */

namespace AmoCrm\Client\Object;


use AmoCrm\Client\CustomField\FieldConfig;

class Lead {

  /**
   * AmoCRM ID
   *
   * @var string
   */
  protected $id = NULL;

  /**
   * Price of the lead
   *
   * @var float
   */
  protected $price = NULL;

  /**
   * Responsible user ID
   *
   * @var integer
   */
  protected $responsible_user_id = NULL;

  /**
   * Name of the lead
   *
   * @var string
   */
  protected $name = NULL;

  /**
   * Tags
   *
   * @var array
   */
  protected $tags = [];

  /**
   * Unix timestamp
   *
   * @var integer
   */
  protected $date_create = NULL;

  /**
   * @var integer
   */
  protected $pipeline_id = NULL;

  /**
   * @var integer
   */
  protected $status_id = NULL;

  /**
   * ID of the entity in the client system
   *
   * @var string
   */
  protected $request_id = NULL;

  /**
   * @var null
   */
  protected $_rel_contact_hash = NULL;

  /**
   * Special hash key to link entities
   *
   * @var string
   */
  protected $hash = NULL;

  /**
   * AmoLeadObject constructor.
   *
   * @param array $data
   * @param \AmoCrm\Client\CustomField\FieldConfig $field_config
   */
  public function __construct(array $data, FieldConfig $field_config) {
    // Creation date - now
    $this->date_create = time();
    // Assign properties
    foreach ($data as $key => $val) {
      switch ($key) {
        case 'tags':
          $this->tags = (is_array($val)) ? $val : explode(',', $val);
          continue;
        default:
          if (property_exists(__CLASS__, $key)) {
            $this->$key = $val;
          }
          continue;
      }
    }
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
    if (!is_null($this->name)) {
      $result['name'] = $this->name;
    }
    if (!is_null($this->price)) {
      $result['price'] = $this->price;
    }
    if (is_array($this->tags)) {
      $result['tags'] = implode(',', $this->tags);
    }
    else {
      $result['tags'] = $this->tags;
    }
    if (!is_null($this->pipeline_id)) {
      $result['pipeline_id'] = $this->pipeline_id;
    }
    if (!is_null($this->status_id)) {
      $result['status_id'] = $this->status_id;
    }
    if (!is_null($this->responsible_user_id)) {
      $result['responsible_user_id'] = $this->responsible_user_id;
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
   * @return Lead
   */
  public function setId($id) {
    $this->id = $id;
    return $this;
  }

  /**
   * @return float
   */
  public function getPrice() {
    return $this->price;
  }

  /**
   * @param float $price
   * @return Lead
   */
  public function setPrice($price) {
    $this->price = $price;
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
   * @return Lead
   */
  public function setResponsibleUserId($responsible_user_id) {
    $this->responsible_user_id = $responsible_user_id;
    return $this;
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @param string $name
   * @return Lead
   */
  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  /**
   * @return array
   */
  public function getTags() {
    return $this->tags;
  }

  /**
   * @param array $tags
   * @return Lead
   */
  public function setTags($tags) {
    $this->tags = $tags;
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
   * @return Lead
   */
  public function setDateCreate($date_create) {
    $this->date_create = $date_create;
    return $this;
  }

  /**
   * @return int
   */
  public function getPipelineId() {
    return $this->pipeline_id;
  }

  /**
   * @param int $pipeline_id
   * @return Lead
   */
  public function setPipelineId($pipeline_id) {
    $this->pipeline_id = $pipeline_id;
    return $this;
  }

  /**
   * @return int
   */
  public function getStatusId() {
    return $this->status_id;
  }

  /**
   * @param int $status_id
   * @return Lead
   */
  public function setStatusId($status_id) {
    $this->status_id = $status_id;
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
   * @return Lead
   */
  public function setRequestId($request_id) {
    $this->request_id = $request_id;
    return $this;
  }

  /**
   * @return string
   */
  public function getHash() {
    return $this->hash;
  }

  /**
   * @param string $hash
   * @return Lead
   */
  public function setHash($hash) {
    $this->hash = $hash;
    return $this;
  }

}
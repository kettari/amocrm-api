<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 26.07.2016
 * Time: 15:55
 */

namespace AmoCrm\Api\Objects;

use AmoCrm\Api\CustomFields\AmoCustomField;


/**
 * Class AmoContactObject
 *
 * @package AmoCrm\Api
 */
class AmoContactObject {

  /**
   * AmoCRM ID
   *
   * @var string
   */
  protected $id = NULL;

  /**
   * Contact name
   *
   * @var string
   */
  protected $name = NULL;

  /**
   * Array of AmoPhone***CustomField
   *
   * @var array
   */
  protected $phones = [];

  /**
   * Email
   *
   * @var array
   */
  protected $emails = [];

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
   * Responsible user ID
   *
   * @var integer
   */
  protected $responsible_user_id = NULL;

  /**
   * Linked leads IDs
   *
   * @var array
   */
  protected $linked_leads_id = [];

  /**
   * ID of the entity in the client system
   *
   * @var string
   */
  protected $request_id = NULL;

  /**
   * Special hash key to link entities
   *
   * @var string
   */
  protected $hash = NULL;

  /**
   * AmoContactObject constructor.
   *
   * @param array $data
   */
  public function __construct(array $data) {
    // Creation date - now
    $this->date_create = time();
    // Assign properties
    foreach ($data as $key => $val) {
      switch ($key) {
        case 'tags':
          $this->tags = (is_array($val)) ? $val : explode(',', $val);
          continue;
        case 'custom_fields':
          $this->constructCustomFields($val);
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
   * @inheritDoc
   */

  /**
   * Construct custom fields
   *
   * @param array $custom_fields
   */
  protected function constructCustomFields(array $custom_fields) {
    // NOP
  }

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
    // Custom fields
    $custom_fields = [];

    // Prepare phones
    /** @var AmoCustomField $phone */
    foreach ($this->phones as $phone) {
      if (is_object($phone)) {
        $custom_fields[] = $phone->getArray();
      }
    }
    // Emails
    /** @var AmoCustomField $email */
    foreach ($this->emails as $email) {
      if (is_object($email)) {
        $custom_fields[] = $email->getArray();
      }
    }

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
    if (count($custom_fields) > 0) {
      $result['custom_fields'] = $custom_fields;
    }
    if (count($this->tags) > 0) {
      $result['tags'] = implode(',', $this->tags);
    }
    if (!is_null($this->responsible_user_id)) {
      $result['responsible_user_id'] = $this->responsible_user_id;
    }
    if (count($this->linked_leads_id) > 0) {
      $result['linked_leads_id'] = $this->linked_leads_id;
    }

    return $result;
  }

  /**
   * @return string
   */
  /*public function getPhones() {
    $phones = [];
    if (is_array($this->phones)) {
      /** @var AmoCustomField $phone *
      foreach ($this->phones as $phone) {
        if (is_object($phone)) {
          $phones[] = $phone->getValue();
        }
      }
    }
    return implode(', ', $phones);
  }*/

  /**
   * @return array
   */
  public function getPhonesArray() {
    $phones = [];
    if (is_array($this->phones)) {
      /** @var AmoCustomField $phone */
      foreach ($this->phones as $phone) {
        if (is_object($phone)) {
          $phones[] = $phone->getValue();
        }
      }
    }
    return $phones;
  }

  /**
   * @return string
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @param string $id
   * @return AmoContactObject
   */
  public function setId($id) {
    $this->id = $id;
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
   * @return AmoContactObject
   */
  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  /**
   * @return array
   */
  public function getPhones() {
    return $this->phones;
  }

  /**
   * @param array $phones
   * @return AmoContactObject
   */
  public function setPhones($phones) {
    $this->phones = $phones;
    return $this;
  }

  /**
   * @return array
   */
  public function getEmails() {
    return $this->emails;
  }

  /**
   * @param array $emails
   * @return AmoContactObject
   */
  public function setEmails($emails) {
    $this->emails = $emails;
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
   * @return AmoContactObject
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
   * @return AmoContactObject
   */
  public function setDateCreate($date_create) {
    $this->date_create = $date_create;
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
   * @return AmoContactObject
   */
  public function setResponsibleUserId($responsible_user_id) {
    $this->responsible_user_id = $responsible_user_id;
    return $this;
  }

  /**
   * @return array
   */
  public function getLinkedLeadsId() {
    return $this->linked_leads_id;
  }

  /**
   * @param array $linked_leads_id
   * @return AmoContactObject
   */
  public function setLinkedLeadsId($linked_leads_id) {
    $this->linked_leads_id = $linked_leads_id;
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
   * @return AmoContactObject
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
   * @return AmoContactObject
   */
  public function setHash($hash) {
    $this->hash = $hash;
    return $this;
  }

}
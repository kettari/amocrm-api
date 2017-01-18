<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 18.01.2017
 * Time: 20:19
 */

namespace AmoCrm\Client\Object;

use AmoCrm\Client\CustomField\EmailCustomField;
use AmoCrm\Client\CustomField\EmailFieldFactory;
use AmoCrm\Client\CustomField\FieldConfig;
use AmoCrm\Client\CustomField\PhoneCustomField;
use AmoCrm\Client\CustomField\PhoneFieldFactory;

class Contact {

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
   * Contact constructor.
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
        case 'custom_fields':
          $this->constructCustomFields($val, $field_config);
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
   * Construct custom fields
   *
   * @param array $custom_fields
   * @param \AmoCrm\Client\CustomField\FieldConfig $field_config
   */
  protected function constructCustomFields(array $custom_fields, FieldConfig $field_config) {
    // Analyze fields one by one
    foreach ($custom_fields as $field) {
      // Check each custom field ID
      switch ($field['id']) {
        case $field_config->getFieldPhoneId():
          $this->phones[] = PhoneFieldFactory::build(
            $field_config,
            $field['values'][0]['enum'],
            $field['values'][0]['value']
          );
          break;
        case $field_config->getFieldEmailId():
          $this->emails[] = EmailFieldFactory::build(
            $field_config,
            $field['values'][0]['enum'],
            $field['values'][0]['value']
          );
          break;
        /*case AmoCustomAccountConstants::getCustomFieldTallantoUrl():
          $this->tallanto_url = new AmoTallantoUrlCustomField($field['values'][0]['value']);
          break;*/
      }
    }
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
    /** @var PhoneCustomField $phone */
    foreach ($this->phones as $phone) {
      if (is_object($phone)) {
        $custom_fields[] = $phone->getArray();
      }
    }
    // Emails
    /** @var EmailCustomField $email */
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
  public function getId() {
    return $this->id;
  }

  /**
   * @param string $id
   * @return Contact
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
   * @return Contact
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
   * @return Contact
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
   * @return Contact
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
   * @return Contact
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
   * @return Contact
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
   * @return Contact
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
   * @return Contact
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
   * @return Contact
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
   * @return Contact
   */
  public function setHash($hash) {
    $this->hash = $hash;
    return $this;
  }

}
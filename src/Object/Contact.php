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

class Contact extends AbstractTaggableEntity {

  /**
   * Contact name
   *
   * @var string
   */
  protected $name;

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
   * Linked leads IDs
   *
   * @var array
   */
  protected $leads = [];

  /**
   * Contact constructor.
   *
   * @param array $data
   * @param \AmoCrm\Client\CustomField\FieldConfig $field_config
   */
  public function __construct(array $data, FieldConfig $field_config) {
    parent::__construct($data);

    // Assign properties
    foreach ($data as $key => $val) {
      switch ($key) {
        case 'custom_fields':
          $this->constructCustomFields($val, $field_config);
          continue;
          break;
        case 'leads':
          $ids = $val['id'] ?? [];
          $this->setLinkedLeadsId($ids);
          continue;
          break;
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
          // Iterate all enum elements
          foreach ($field['values'] as $item) {
            $this->phones[] = PhoneFieldFactory::build($field_config,
              $item['enum'], $item['value']);
          }
          break;
        case $field_config->getFieldEmailId():
          // Iterate all enum elements
          foreach ($field['values'] as $item) {
            $this->emails[] = EmailFieldFactory::build($field_config,
              $item['enum'], $item['value']);
          }
          break;
      }
    }
  }

  /**
   * Return array ready to be sent to AmoCRM
   *
   * @return array
   */
  public function toArray() {
    // Build result array
    $result = parent::toArray();
    $result['name'] = $this->getName();
    $result['linked_leads_id'] = $this->getLinkedLeadsId();

      // Custom fields
    $custom_fields = [];

    // Prepare phones
    /** @var PhoneCustomField $phone */
    foreach ($this->phones as $phone) {
      if ($phone instanceof PhoneCustomField) {
        $custom_fields[] = $phone->toArray();
      }
    }
    // Emails
    /** @var EmailCustomField $email */
    foreach ($this->emails as $email) {
      if ($email instanceof EmailCustomField) {
        $custom_fields[] = $email->toArray();
      }
    }

    if (count($custom_fields) > 0) {
      $result['custom_fields'] = $custom_fields;
    }

    return $result;
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
  public function getLinkedLeadsId() {
    return $this->leads;
  }

  /**
   * @param array $linked_leads_id
   * @return Contact
   */
  public function setLinkedLeadsId($linked_leads_id) {
    $this->leads = $linked_leads_id;

    return $this;
  }
}
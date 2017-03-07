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

class Contact extends AbstractTimeAwareEntity {

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
   * Tags
   *
   * @var array
   */
  protected $tags = [];

  /**
   * Linked leads IDs
   *
   * @var array
   */
  protected $linked_leads_id = [];

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
        case 'tags':
          $this->tags = (is_array($val)) ? $val : explode(',', $val);
          continue;
        case 'custom_fields':
          $this->constructCustomFields($val, $field_config);
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
          $this->phones[] = PhoneFieldFactory::build($field_config,
            $field['values'][0]['enum'], $field['values'][0]['value']);
          break;
        case $field_config->getFieldEmailId():
          $this->emails[] = EmailFieldFactory::build($field_config,
            $field['values'][0]['enum'], $field['values'][0]['value']);
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
  public function toArray() {
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

    // Build result array
    $result = parent::toArray();
    if (count($custom_fields) > 0) {
      $result['custom_fields'] = $custom_fields;
    }
    if (count($this->tags) > 0) {
      $result['tags'] = implode(',', $this->tags);
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
}
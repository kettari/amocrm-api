<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 18.01.2017
 * Time: 20:32
 */

namespace AmoCrm\Client\CustomField;


class FieldConfig {

  // Phone
  protected $field_phone_id;
  protected $field_phone_enum_mob;
  protected $field_phone_enum_work;
  protected $field_phone_enum_work_add;
  protected $field_phone_enum_home;
  protected $field_phone_enum_other;
  protected $field_phone_enum_fax;

  // Email
  protected $field_email_id;
  protected $field_email_enum_priv;
  protected $field_email_enum_work;
  protected $field_email_enum_other;

  /**
   * @return mixed
   */
  public function getFieldPhoneId() {
    return $this->field_phone_id;
  }

  /**
   * @param mixed $field_phone_id
   * @return FieldConfig
   */
  public function setFieldPhoneId($field_phone_id) {
    $this->field_phone_id = $field_phone_id;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getFieldPhoneEnumMob() {
    return $this->field_phone_enum_mob;
  }

  /**
   * @param mixed $field_phone_enum_mob
   * @return FieldConfig
   */
  public function setFieldPhoneEnumMob($field_phone_enum_mob) {
    $this->field_phone_enum_mob = $field_phone_enum_mob;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getFieldPhoneEnumWork() {
    return $this->field_phone_enum_work;
  }

  /**
   * @param mixed $field_phone_enum_work
   * @return FieldConfig
   */
  public function setFieldPhoneEnumWork($field_phone_enum_work) {
    $this->field_phone_enum_work = $field_phone_enum_work;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getFieldPhoneEnumWorkAdd() {
    return $this->field_phone_enum_work_add;
  }

  /**
   * @param mixed $field_phone_enum_work_add
   * @return FieldConfig
   */
  public function setFieldPhoneEnumWorkAdd($field_phone_enum_work_add) {
    $this->field_phone_enum_work_add = $field_phone_enum_work_add;

    return $this;
  }

  /**
   * @return mixed
   */
  public function getFieldPhoneEnumHome() {
    return $this->field_phone_enum_home;
  }

  /**
   * @param mixed $field_phone_enum_home
   * @return FieldConfig
   */
  public function setFieldPhoneEnumHome($field_phone_enum_home) {
    $this->field_phone_enum_home = $field_phone_enum_home;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getFieldPhoneEnumOther() {
    return $this->field_phone_enum_other;
  }

  /**
   * @param mixed $field_phone_enum_other
   * @return FieldConfig
   */
  public function setFieldPhoneEnumOther($field_phone_enum_other) {
    $this->field_phone_enum_other = $field_phone_enum_other;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getFieldPhoneEnumFax() {
    return $this->field_phone_enum_fax;
  }

  /**
   * @param mixed $field_phone_enum_fax
   * @return FieldConfig
   */
  public function setFieldPhoneEnumFax($field_phone_enum_fax) {
    $this->field_phone_enum_fax = $field_phone_enum_fax;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getFieldEmailId() {
    return $this->field_email_id;
  }

  /**
   * @param mixed $field_email_id
   * @return FieldConfig
   */
  public function setFieldEmailId($field_email_id) {
    $this->field_email_id = $field_email_id;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getFieldEmailEnumPriv() {
    return $this->field_email_enum_priv;
  }

  /**
   * @param mixed $field_email_enum_priv
   * @return FieldConfig
   */
  public function setFieldEmailEnumPriv($field_email_enum_priv) {
    $this->field_email_enum_priv = $field_email_enum_priv;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getFieldEmailEnumWork() {
    return $this->field_email_enum_work;
  }

  /**
   * @param mixed $field_email_enum_work
   * @return FieldConfig
   */
  public function setFieldEmailEnumWork($field_email_enum_work) {
    $this->field_email_enum_work = $field_email_enum_work;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getFieldEmailEnumOther() {
    return $this->field_email_enum_other;
  }

  /**
   * @param mixed $field_email_enum_other
   * @return FieldConfig
   */
  public function setFieldEmailEnumOther($field_email_enum_other) {
    $this->field_email_enum_other = $field_email_enum_other;
    return $this;
  }


}
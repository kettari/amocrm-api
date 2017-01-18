<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 18.01.2017
 * Time: 20:51
 */

namespace AmoCrm\Client\CustomField;


use AmoCrm\Client\Exception\CustomFieldUnknownEnumException;

class EmailFieldFactory {
  /**
   * @param \AmoCrm\Client\CustomField\FieldConfig $field_config
   * @param $enum_id
   * @param $value
   * @return \AmoCrm\Client\CustomField\OtherEmailCustomField|\AmoCrm\Client\CustomField\PrivateEmailCustomField|\AmoCrm\Client\CustomField\WorkEmailCustomField
   * @throws \AmoCrm\Client\Exception\CustomFieldUnknownEnumException
   */
  public static function build(FieldConfig $field_config, $enum_id, $value) {
    switch ($enum_id) {
      case $field_config->getFieldEmailEnumPriv():
        return new PrivateEmailCustomField($field_config->getFieldPhoneId(), $field_config->getFieldEmailEnumPriv(), $value);
      case $field_config->getFieldEmailEnumWork():
        return new WorkEmailCustomField($field_config->getFieldPhoneId(), $field_config->getFieldEmailEnumWork(), $value);
      case $field_config->getFieldEmailEnumOther():
        return new OtherEmailCustomField($field_config->getFieldPhoneId(), $field_config->getFieldEmailEnumOther(), $value);
      default:
        throw new CustomFieldUnknownEnumException(sprintf('Unknown custom email field enum = %s', $enum_id));
        break;
    }
  }
}
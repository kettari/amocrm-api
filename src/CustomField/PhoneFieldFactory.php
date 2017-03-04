<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 18.01.2017
 * Time: 20:51
 */

namespace AmoCrm\Client\CustomField;


use AmoCrm\Client\Exception\CustomFieldUnknownEnumException;

class PhoneFieldFactory {
  /**
   * @param \AmoCrm\Client\CustomField\FieldConfig $field_config
   * @param $enum_id
   * @param $value
   * @return \AmoCrm\Client\CustomField\FaxPhoneCustomField|\AmoCrm\Client\CustomField\HomePhoneCustomField|\AmoCrm\Client\CustomField\MobilePhoneCustomField|\AmoCrm\Client\CustomField\OtherPhoneCustomField|\AmoCrm\Client\CustomField\WorkPhoneCustomField
   * @throws \AmoCrm\Client\Exception\CustomFieldUnknownEnumException
   */
  public static function build(FieldConfig $field_config, $enum_id, $value) {
    switch ($enum_id) {
      case $field_config->getFieldPhoneEnumMob():
        return new MobilePhoneCustomField($field_config->getFieldPhoneId(), $field_config->getFieldPhoneEnumMob(), $value);
      case $field_config->getFieldPhoneEnumHome():
        return new HomePhoneCustomField($field_config->getFieldPhoneId(), $field_config->getFieldPhoneEnumHome(), $value);
      case $field_config->getFieldPhoneEnumWork():
        return new WorkPhoneCustomField($field_config->getFieldPhoneId(), $field_config->getFieldPhoneEnumWork(), $value);
      case $field_config->getFieldPhoneEnumFax():
        return new FaxPhoneCustomField($field_config->getFieldPhoneId(), $field_config->getFieldPhoneEnumFax(), $value);
      case $field_config->getFieldPhoneEnumOther():
        return new OtherPhoneCustomField($field_config->getFieldPhoneId(), $field_config->getFieldPhoneEnumOther(), $value);
      // TODO: Add Phone enum WORKADD ('direct phone') or it will crash for contacts with this field filled
      default:
        throw new CustomFieldUnknownEnumException(sprintf('Unknown custom phone field enum = %s', $enum_id));
        break;
    }
  }
}
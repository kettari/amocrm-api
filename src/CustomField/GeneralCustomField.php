<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 18.01.2017
 * Time: 20:26
 */

namespace AmoCrm\Client\CustomField;


class GeneralCustomField {

  /**
   * @var string
   */
  protected $id;

  /**
   * @var string
   */
  protected $enum;

  /**
   * @var string
   */
  protected $value;

  /**
   * AmoCustomField constructor.
   *
   * @param string $id
   * @param string $enum
   * @param string $value
   */
  public function __construct($id, $enum, $value) {
    $this->id = $id;
    $this->enum = $enum;
    $this->value = $value;
  }

  /**
   * Return array ready to be sent to AmoCRM
   *
   * @return array
   */
  public function getArray() {
    $result = [
      'id'     => $this->id,
      'values' => [
        [
          'value' => $this->value,
        ],
      ],
    ];
    if (!is_null($this->enum)) {
      $result['values'][0]['enum'] = $this->enum;
    }
    return $result;
  }

  /**
   * @return string
   */
  public function getValue() {
    return $this->value;
  }

}
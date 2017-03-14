<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 07.03.2017
 * Time: 16:07
 */

namespace AmoCrm\Client\Object;


abstract class AbstractEntity {

  /**
   * AbstractEntity constructor.
   *
   * @param array $data
   */
  public function __construct(array $data) {
    foreach ($data as $key => $val) {
      if (property_exists($this, $key)) {
        $this->$key = $val;
      }
    }
  }

  /**
   * Return array ready to be sent to AmoCRM
   *
   * @return array
   */
  abstract public function toArray();
}
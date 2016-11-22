<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 16.08.2016
 * Time: 17:02
 */

namespace AmoCrm\Api\Objects;


/**
 * Class AmoPipelineObject
 *
 * @package AmoCrm\Api\Object
 */
class AmoPipelineObject {

  /**
   * @var integer
   */
  protected $id;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var integer
   */
  protected $sort;

  /**
   * @var bool
   */
  protected $is_main;

  /**
   * Array of AmoStatusObject
   *
   * @var array
   */
  protected $statuses;

  /**
   * AmoPipelineObject constructor.
   *
   * @param array $data
   */
  public function __construct(array $data) {
    foreach ($data as $key => $val) {
      if ('statuses' == $key) {
         if (is_array($val)) {
           foreach ($val as $id => $status) {
             $this->statuses[$id] = new AmoStatusObject($status);
           }
         }
      } // Other properties
      elseif (property_exists(__CLASS__, $key)) {
        $this->$key = $val;
      }
    }
  }

  /**
   * @return int
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @param int $id
   * @return AmoPipelineObject
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
   * @return AmoPipelineObject
   */
  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  /**
   * @return int
   */
  public function getSort() {
    return $this->sort;
  }

  /**
   * @param int $sort
   * @return AmoPipelineObject
   */
  public function setSort($sort) {
    $this->sort = $sort;
    return $this;
  }

  /**
   * @return boolean
   */
  public function isIsMain() {
    return $this->is_main;
  }

  /**
   * @param boolean $is_main
   * @return AmoPipelineObject
   */
  public function setIsMain($is_main) {
    $this->is_main = $is_main;
    return $this;
  }

  /**
   * @return array
   */
  public function getStatuses() {
    return $this->statuses;
  }

  /**
   * @param array $statuses
   * @return AmoPipelineObject
   */
  public function setStatuses($statuses) {
    $this->statuses = $statuses;
    return $this;
  }

}
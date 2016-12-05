<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 16.08.2016
 * Time: 17:02
 */

namespace AmoCrm\Api\Object;

/**
 * Class AmoStatusObject
 *
 * @package AmoCrm\Api\Object
 */
class AmoStatusObject {

  /**
   * @var integer
   */
  protected $id;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var string
   */
  protected $color;

  /**
   * @var integer
   */
  protected $sort;

  /**
   * @var string
   */
  protected $editable;

  /**
   * @var integer
   */
  protected $pipeline_id;

  /**
   * AmoStatusObject constructor.
   *
   * @param array $data
   */
  public function __construct(array $data) {
    foreach ($data as $key => $val) {
      // Other properties
      if (property_exists(__CLASS__, $key)) {
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
   * @return AmoStatusObject
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
   * @return AmoStatusObject
   */
  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  /**
   * @return string
   */
  public function getColor() {
    return $this->color;
  }

  /**
   * @param string $color
   * @return AmoStatusObject
   */
  public function setColor($color) {
    $this->color = $color;
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
   * @return AmoStatusObject
   */
  public function setSort($sort) {
    $this->sort = $sort;
    return $this;
  }

  /**
   * @return string
   */
  public function getEditable() {
    return $this->editable;
  }

  /**
   * @param string $editable
   * @return AmoStatusObject
   */
  public function setEditable($editable) {
    $this->editable = $editable;
    return $this;
  }

  /**
   * @return int
   */
  public function getPipelineId() {
    return $this->pipeline_id;
  }

  /**
   * @param int $pipeline_id
   * @return AmoStatusObject
   */
  public function setPipelineId($pipeline_id) {
    $this->pipeline_id = $pipeline_id;
    return $this;
  }

}
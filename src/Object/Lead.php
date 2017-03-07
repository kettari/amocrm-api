<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 19.01.2017
 * Time: 0:49
 */

namespace AmoCrm\Client\Object;


class Lead extends AbstractTaggableEntity {

  /**
   * Price of the lead
   *
   * @var float
   */
  protected $price = NULL;

  /**
   * Name of the lead
   *
   * @var string
   */
  protected $name = NULL;

  /**
   * @var integer
   */
  protected $pipeline_id = NULL;

  /**
   * @var integer
   */
  protected $status_id = NULL;

  /**
   * ID of the entity in the client system
   *
   * @var string
   */
  protected $request_id = NULL;

  /**
   * @return float
   */
  public function getPrice() {
    return $this->price;
  }

  /**
   * @param float $price
   * @return Lead
   */
  public function setPrice($price) {
    $this->price = $price;
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
   * @return Lead
   */
  public function setName($name) {
    $this->name = $name;
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
   * @return Lead
   */
  public function setPipelineId($pipeline_id) {
    $this->pipeline_id = $pipeline_id;
    return $this;
  }

  /**
   * @return int
   */
  public function getStatusId() {
    return $this->status_id;
  }

  /**
   * @param int $status_id
   * @return Lead
   */
  public function setStatusId($status_id) {
    $this->status_id = $status_id;
    return $this;
  }

}
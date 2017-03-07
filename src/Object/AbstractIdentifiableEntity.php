<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 07.03.2017
 * Time: 16:10
 */

namespace AmoCrm\Client\Object;


abstract class AbstractIdentifiableEntity extends AbstractEntity {

  /**
   * AmoCRM ID
   *
   * @var string
   */
  protected $id;

  /**
   * ID of the entity in the client system
   *
   * @var string
   */
  protected $request_id;

  /**
   * @return string
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @param string $id
   * @return AbstractIdentifiableEntity
   */
  public function setId($id) {
    $this->id = $id;

    return $this;
  }

  /**
   * @return string
   */
  public function getRequestId() {
    return $this->request_id;
  }

  /**
   * @param string $request_id
   * @return AbstractIdentifiableEntity
   */
  public function setRequestId($request_id) {
    $this->request_id = $request_id;

    return $this;
  }

}
<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 07.03.2017
 * Time: 16:20
 */

namespace AmoCrm\Client\Object;


abstract class AbstractTimeAwareEntity extends AbstractIdentifiableEntity {

  /**
   * Unix timestamp
   *
   * @var int
   */
  protected $date_create;

  /**
   * Unix timestamp
   *
   * @var int
   */
  protected $last_modified;

  /**
   * Responsible user ID
   *
   * @var integer
   */
  protected $responsible_user_id;

  /**
   * Creator user ID
   *
   * @var integer
   */
  protected $created_user_id = NULL;

  /**
   * Contact constructor.
   *
   * @param array $data
   */
  public function __construct(array $data) {
    parent::__construct($data);

    // Creation date - now
    $this->date_create = is_null($this->date_create) ?: time();
    $this->last_modified = is_null($this->last_modified) ?: time();
  }

  /**
   * @return int
   */
  public function getDateCreate() {
    return $this->date_create;
  }

  /**
   * @param int $date_create
   * @return AbstractTimeAwareEntity
   */
  public function setDateCreate($date_create) {
    $this->date_create = $date_create;

    return $this;
  }

  /**
   * @return int
   */
  public function getLastModified() {
    return $this->last_modified;
  }

  /**
   * @param int $last_modified
   * @return AbstractTimeAwareEntity
   */
  public function setLastModified($last_modified) {
    $this->last_modified = $last_modified;

    return $this;
  }

  /**
   * @return int
   */
  public function getResponsibleUserId() {
    return $this->responsible_user_id;
  }

  /**
   * @param int $responsible_user_id
   * @return AbstractTimeAwareEntity
   */
  public function setResponsibleUserId($responsible_user_id) {
    $this->responsible_user_id = $responsible_user_id;

    return $this;
  }

  /**
   * @return int
   */
  public function getCreatedUserId() {
    return $this->created_user_id;
  }

  /**
   * @param int $created_user_id
   * @return AbstractTimeAwareEntity
   */
  public function setCreatedUserId($created_user_id) {
    $this->created_user_id = $created_user_id;

    return $this;
  }

}
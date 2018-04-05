<?php

namespace AmoCrm\Client\Object;

abstract class AbstractDeletableEntity extends AbstractTaggableEntity {

  /**
   * @var bool
   */
  protected $isDeleted = false;

  /**
   * AbstractDeletableEntity constructor.
   *
   * @param array $data
   */
  public function __construct(array $data)
  {
    parent::__construct($data);

    if (isset($data['is_deleted'])) {
      $this->isDeleted = (bool) $data['is_deleted'];
    }
  }

  /**
   * Return array ready to be sent to AmoCRM
   *
   * @return array
   */
  public function toArray() {
    // Build result array
    $result = parent::toArray();
    $result['is_deleted'] = $this->isDeleted;

    return $result;
  }

  /**
   * @return bool
   */
  public function getIsDeleted() {
    return $this->isDeleted;
  }

  /**
   * @param array $isDeleted
   *
   * @return $this
   */
  public function setIsDeleted($isDeleted) {
    $this->isDeleted = $isDeleted;

    return $this;
  }
}

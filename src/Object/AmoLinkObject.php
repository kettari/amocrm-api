<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.09.2016
 * Time: 18:56
 */

namespace AmoCrm\Api\Object;


/**
 * Class AmoLinkObject
 *
 * @package AmoCrm\Api\Object
 */
class AmoLinkObject {

  /**
   * @var integer
   */
  protected $contact_id;

  /**
   * @var integer
   */
  protected $lead_id;

  /**
   * @var integer
   */
  protected $last_modified;

  /**
   * AmoLinkObject constructor.
   *
   * @param array $data
   */
  public function __construct(array $data) {
    // Assign properties
    foreach ($data as $key => $val) {
      if (property_exists(__CLASS__, $key)) {
        $this->$key = $val;
      }
    }
  }

  /**
   * @return int
   */
  public function getContactId() {
    return $this->contact_id;
  }

  /**
   * @param int $contact_id
   * @return AmoLinkObject
   */
  public function setContactId($contact_id) {
    $this->contact_id = $contact_id;
    return $this;
  }

  /**
   * @return int
   */
  public function getLeadId() {
    return $this->lead_id;
  }

  /**
   * @param int $lead_id
   * @return AmoLinkObject
   */
  public function setLeadId($lead_id) {
    $this->lead_id = $lead_id;
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
   * @return AmoLinkObject
   */
  public function setLastModified($last_modified) {
    $this->last_modified = $last_modified;
    return $this;
  }

}
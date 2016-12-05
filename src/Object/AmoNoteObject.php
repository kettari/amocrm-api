<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 27.07.2016
 * Time: 15:21
 */

namespace AmoCrm\Api\Object;


/**
 * Class AmoNoteObject
 *
 * @package AmoCrm\Api
 */
class AmoNoteObject {

  /**
   * Note types
   */
  const NOTE_TYPE_COMMON = 4;

  /**
   * Element types
   */
  const ELEMENT_TYPE_CONTACT = 1;
  const ELEMENT_TYPE_LEAD = 2;
  const ELEMENT_TYPE_COMPANY = 3;

  /**
   * AmoCRM ID
   *
   * @var string
   */
  protected $id = NULL;

  /**
   * Lead or contact id
   *
   * @var integer
   */
  protected $element_id = NULL;

  /**
   * Type of the related element:
   *  1 - contact
   *  2 - lead
   *  3 - company
   *  4 - task (result of the task)
   *
   * @var integer
   */
  protected $element_type = NULL;

  /**
   * Note type (see above):
   *  1 - lead created
   *  2 - contact created
   *  3 - lead status changed
   *  4 - common note
   *  5 - file
   *  6 - incoming call from iphone-apps
   *  7 - unused
   *  8 - unused
   *  9 - unused
   *  10 - incoming call
   *  11 - outgoing call
   *  12 - company created
   *  13 - task result
   *  102 - incoming sms
   *  103 - outgoing sms
   * @var integer
   */
  protected $note_type = NULL;

  /**
   * Creator user ID
   *
   * @var integer
   */
  protected $created_user_id = NULL;

  /**
   * Unix timestamp (create)
   *
   * @var integer
   */
  protected $date_create = NULL;

  /**
   * Unix timestamp (modify)
   *
   * @var integer
   */
  protected $last_modified = NULL;

  /**
   * Multi string text of the note
   *
   * @var string
   */
  protected $text;

  /**
   * ID of the entity in the client system
   *
   * @var string
   */
  protected $request_id = NULL;

  /**
   * AmoNoteObject constructor.
   *
   * @param array $data
   */
  public function __construct(array $data) {
    // Creation date - now
    $this->date_create = time();
    $this->last_modified = time();
    // Assign properties
    foreach ($data as $key => $val) {
      switch ($key) {
        default:
          if (property_exists(__CLASS__, $key)) {
            $this->$key = $val;
          }
          continue;
      }
    }
  }

  /**
   * Return array ready to be sent to AmoCRM
   *
   * @return array
   */
  public function getArray() {
    // Build result array
    $result = [
      'date_create'   => $this->date_create,
      'last_modified' => time(),
      'request_id'    => $this->request_id,
    ];

    if (!is_null($this->id)) {
      $result['id'] = $this->id;
    }
    if (!is_null($this->element_id)) {
      $result['element_id'] = $this->element_id;
    }
    if (!is_null($this->element_type)) {
      $result['element_type'] = $this->element_type;
    }
    if (!is_null($this->note_type)) {
      $result['note_type'] = $this->note_type;
    }
    if (!is_null($this->last_modified)) {
      $result['last_modified'] = $this->last_modified;
    }
    if (!is_null($this->text)) {
      $result['text'] = $this->text;
    }
    if (!is_null($this->created_user_id)) {
      $result['created_user_id'] = $this->created_user_id;
    }
    return $result;
  }

  /**
   * @return string
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @param string $id
   * @return AmoNoteObject
   */
  public function setId($id) {
    $this->id = $id;
    return $this;
  }

  /**
   * @return int
   */
  public function getElementId() {
    return $this->element_id;
  }

  /**
   * @param int $element_id
   * @return AmoNoteObject
   */
  public function setElementId($element_id) {
    $this->element_id = $element_id;
    return $this;
  }

  /**
   * @return int
   */
  public function getElementType() {
    return $this->element_type;
  }

  /**
   * @param int $element_type
   * @return AmoNoteObject
   */
  public function setElementType($element_type) {
    $this->element_type = $element_type;
    return $this;
  }

  /**
   * @return int
   */
  public function getNoteType() {
    return $this->note_type;
  }

  /**
   * @param int $note_type
   * @return AmoNoteObject
   */
  public function setNoteType($note_type) {
    $this->note_type = $note_type;
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
   * @return AmoNoteObject
   */
  public function setCreatedUserId($created_user_id) {
    $this->created_user_id = $created_user_id;
    return $this;
  }

  /**
   * @return int
   */
  public function getDateCreate() {
    return $this->date_create;
  }

  /**
   * @param int $date_create
   * @return AmoNoteObject
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
   * @return AmoNoteObject
   */
  public function setLastModified($last_modified) {
    $this->last_modified = $last_modified;
    return $this;
  }

  /**
   * @return string
   */
  public function getText() {
    return $this->text;
  }

  /**
   * @param string $text
   * @return AmoNoteObject
   */
  public function setText($text) {
    $this->text = $text;
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
   * @return AmoNoteObject
   */
  public function setRequestId($request_id) {
    $this->request_id = $request_id;
    return $this;
  }
}
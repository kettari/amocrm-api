<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 19.01.2017
 * Time: 0:49
 */

namespace AmoCrm\Client\Object;


class Note extends AbstractTimeAwareEntity {

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
   * Lead or contact id
   *
   * @var integer
   */
  protected $element_id;

  /**
   * Type of the related element:
   *  1 - contact
   *  2 - lead
   *  3 - company
   *  4 - task (result of the task)
   *
   * @var integer
   */
  protected $element_type;

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
  protected $note_type;

  /**
   * Multi string text of the note
   *
   * @var string
   */
  protected $text;

  /**
   * @return int
   */
  public function getElementId() {
    return $this->element_id;
  }

  /**
   * @param int $element_id
   * @return Note
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
   * @return Note
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
   * @return Note
   */
  public function setNoteType($note_type) {
    $this->note_type = $note_type;

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
   * @return Note
   */
  public function setText($text) {
    $this->text = $text;

    return $this;
  }

}
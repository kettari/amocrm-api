<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 01.09.2016
 * Time: 16:02
 */

namespace AmoCrm\Api;

use AmoCrm\Api\Object\AmoNoteObject;

class AmoNoteController extends AmoEntityController {

  /**
   * Load list of notes
   *
   * @param int $limit_rows
   * @param int $limit_offset
   * @return \AmoCrm\Api\Response\AmoBaseResponse
   */
  public function roster($limit_rows = 500, $limit_offset = 0) {
    return parent::_find('notes', $limit_rows, $limit_offset);
  }

  /**
   * Create multiple leads in the AmoCRM
   *
   * @param array $notes
   * @return $this
   */
  public function addMultiple(array $notes) {
    // Prepare data for request
    $data = [];
    /** @var AmoNoteObject $note */
    foreach ($notes as $note) {
      if (!($note instanceof AmoNoteObject)) {
        $this->logger->error('Expected AmoNoteObject, got {unexpected_class}',
          [
            'unexpected_class' => get_class($note),
            'source'           => __CLASS__ . '->' . __FUNCTION__,
          ]);
        continue;
      }

      if (!is_null($note->getId())) {
        $data['request']['notes']['update'][] = $note->getArray();
      }
      else {
        $data['request']['notes']['add'][] = $note->getArray();
      }
    }

    $this->buffer = array_merge($this->buffer, $data);

    // Add debug log message
    /*$this->logger->debug(sprintf('Notes addMultiple(): <pre>%s</pre>',
      print_r($this->buffer, TRUE)), ['source' => __CLASS__ . '->' . __FUNCTION__]);*/

    return $this;
  }

  /**
   * @inheritdoc
   */
  public function execute() {
    return parent::_execute('notes');
  }

}
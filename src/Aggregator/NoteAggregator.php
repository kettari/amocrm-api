<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 18.01.2017
 * Time: 18:30
 */

namespace AmoCrm\Client\Aggregator;

use AmoCrm\Client\Exception\AggregatorMethodNotSupportedException;

use AmoCrm\Client\Object\Note;

class NoteAggregator extends GeneralAggregator {

  /**
   * Search in notes not supported.
   *
   * @param $query
   * @return mixed|void
   * @throws \AmoCrm\Client\Exception\AggregatorMethodNotSupportedException
   */
  public function search($query) {
    throw new AggregatorMethodNotSupportedException();
  }

  /**
   * Get the note from the amoCRM using ID
   *
   * @param string $id amoCRM note id
   * @return bool
   */
  public function get($id) {
    $this->clear();
    if ($result = parent::_get('notes', $id)) {
      foreach ($result as $one_item) {
        $this->append(new Note($one_item));
      }

      return TRUE;
    }

    return FALSE;
  }

  /**
   * Add note to the amoCRM database. Copy of the Note object
   * is added to internal storage.
   *
   * @param \AmoCrm\Client\Object\Note $note
   * @throws \AmoCrm\Client\Exception\ResponseAggregatorException
   */
  public function add(Note $note) {
    $this->save('add', $note);
    $this->append(clone $note);
    $this->logger->info('Note added (id={id})', [
      'id' => $note->getId(),
    ]);
  }

  /**
   * Save (add or update) the note
   *
   * @param $operation
   * @param \AmoCrm\Client\Object\Note $note
   * @throws \AmoCrm\Client\Exception\ResponseAggregatorException
   */
  protected function save($operation, Note $note) {
    if ($result = parent::_save('notes', $operation, [$note->toArray()])) {
      $one_item = reset($result);
      $note->setId($one_item['id']);
    }
  }

  /**
   * Update the note in the amoCRM database
   *
   * @param \AmoCrm\Client\Object\Note $note
   * @throws \AmoCrm\Client\Exception\ResponseAggregatorException
   */
  public function update(Note $note) {
    $this->save('update', $note);
    $this->logger->info('Note updated (id={id})', [
      'id' => $note->getId(),
    ]);
  }

}
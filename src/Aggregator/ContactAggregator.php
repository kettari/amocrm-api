<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 18.01.2017
 * Time: 18:30
 */

namespace AmoCrm\Client\Aggregator;

use AmoCrm\Client\Exception\IdentifierAggregatorException;
use AmoCrm\Client\Object\Contact;

class ContactAggregator extends GeneralAggregator {

  /**
   * Search contacts for substring. Searches in name, phone, email
   * and custom fields. Does not search in notes and tasks.
   *
   * @param $query
   * @return bool TRUE if something were found, FALSE otherwise
   * @throws \AmoCrm\Client\Exception\IdentifierAggregatorException
   */
  public function search($query) {
    if (empty($query)) {
      throw new IdentifierAggregatorException('Query is empty');
    }

    return $this->searchEx($query);
  }

  /**
   * Search for substring and load records taking into account pagination.
   *
   * @param $query
   * @param int $page_size
   * @param callable $callback
   * @return bool
   */
  public function searchEx($query, $page_size = 50, callable $callback = NULL) {
    $this->clear();
    if ($result = parent::_load('contacts', $query, $page_size, $callback)) {
      foreach ($result as $one_item) {
        $contact = new Contact($one_item, $this->field_config);
        if (!is_null($callback)) {
          call_user_func_array($callback,
            ['status' => 'converting', 'data' => $contact]);
        }
        $this->append($contact);
      }
      if (!is_null($callback)) {
        call_user_func_array($callback,
          ['status' => 'converted', 'data' => NULL]);
      }

      return TRUE;
    }

    return FALSE;
  }

  /**
   * Get the contact from the amoCRM using ID
   *
   * @param string $id amoCRM contact id
   * @return bool
   */
  public function get($id) {
    $this->clear();
    if ($result = parent::_get('contacts', $id)) {
      foreach ($result as $one_item) {
        $this->append(new Contact($one_item, $this->field_config));
      }

      return TRUE;
    }

    return FALSE;
  }

  /**
   * Add contact to the amoCRM database. Copy of the Contact object
   * is added to internal storage.
   *
   * @param \AmoCrm\Client\Object\Contact $contact
   * @throws \AmoCrm\Client\Exception\ResponseAggregatorException
   */
  public function add(Contact $contact) {
    $this->save('add', $contact);
    $this->append(clone $contact);
    $this->logger->info('Contact "{name}" added (id={id})', [
      'name' => $contact->getName(),
      'id'   => $contact->getId(),
    ]);
  }

  /**
   * Save (add or update) contact
   *
   * @param $operation
   * @param \AmoCrm\Client\Object\Contact $contact
   * @throws \AmoCrm\Client\Exception\ResponseAggregatorException
   */
  protected function save($operation, Contact $contact) {
    if ($result = parent::_save('contacts', $operation,
      [$contact->toArray()])
    ) {
      $one_item = reset($result);
      $contact->setId($one_item['id']);
    }
  }

  /**
   * Update contact in the amoCRM database
   *
   * @param \AmoCrm\Client\Object\Contact $contact
   * @throws \AmoCrm\Client\Exception\ResponseAggregatorException
   */
  public function update(Contact $contact) {
    $this->save('update', $contact);
    $this->logger->info('Contact "{name}" updated (id={id})', [
      'name' => $contact->getName(),
      'id'   => $contact->getId(),
    ]);
  }

}
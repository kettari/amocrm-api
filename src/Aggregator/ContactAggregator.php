<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 18.01.2017
 * Time: 18:30
 */

namespace AmoCrm\Client\Aggregator;

use AmoCrm\Client\Object\Contact;

class ContactAggregator extends GeneralAggregator {

  /**
   * Search contacts for substring. Searches in name, phone, email
   * and custom fields. Does not search in notes and tasks.
   *
   * @param $query
   * @return bool TRUE if something were found, FALSE otherwise
   * @throws \AmoCrm\Client\Exception\QueryAggregatorException
   * @throws \AmoCrm\Client\Exception\ResponseAggregatorException
   */
  public function search($query) {
    $this->clear();
    if ($result = parent::_search('contacts', $query)) {
      foreach ($result as $one_item) {
        $this->append(new Contact($one_item, $this->field_config));
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
    if ($result = parent::_save('contacts', $operation, [$contact->toArray()])) {
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
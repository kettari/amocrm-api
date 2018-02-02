<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 18.01.2017
 * Time: 18:30
 */

namespace AmoCrm\Client\Aggregator;


use AmoCrm\Client\CustomField\FieldConfig;
use AmoCrm\Client\Object\Lead;

class LeadAggregator extends GeneralAggregator {

  /**
   * Search leads for substring. Searches in name, phone, email
   * and custom fields. Does not search in notes and tasks.
   *
   * @param $query
   * @return bool TRUE if something were found, FALSE otherwise
   * @throws \AmoCrm\Client\Exception\QueryAggregatorException
   * @throws \AmoCrm\Client\Exception\ResponseAggregatorException
   */
  public function search($query) {
    $this->clear();
    if ($result = parent::_load('leads', $query)) {
      foreach ($result as $one_item) {
        $this->append($this->createObject($one_item, $this->field_config));
      }

      return TRUE;
    }

    return FALSE;
  }

  /**
   * @return bool
   */
  public function getList()
  {
    $this->clear();
    if ($result = parent::_load('leads', null, 500)) {
      foreach ($result as $one_item) {
        $this->append($this->createObject($one_item, $this->field_config));
      }

      return TRUE;
    }

    return FALSE;
  }

  /**
   * Creates entity object.
   *
   * @param array $data
   * @param \AmoCrm\Client\CustomField\FieldConfig|NULL $field_config
   * @return Lead
   */
  protected function createObject(array $data, FieldConfig $field_config = NULL) {
    return new Lead($data, $field_config);
  }


  /**
   * Get the lead from the amoCRM using ID
   *
   * @param string $id amoCRM lead id
   * @return null|Lead object if found or NULL
   */
  public function get($id) {
    $this->clear();
    if ($result = parent::_get('leads', $id)) {
      foreach ($result as $one_item) {
        $this->append($this->createObject($one_item, $this->field_config));
      }
      $iterator = $this->getIterator();
      $iterator->rewind();

      return $iterator->current();
    }

    return NULL;
  }

  /**
   * Add lead to the amoCRM database. Copy of the Lead object
   * is added to internal storage.
   *
   * @param \AmoCrm\Client\Object\Lead $lead
   * @throws \AmoCrm\Client\Exception\ResponseAggregatorException
   */
  public function add(Lead $lead) {
    $this->save('add', $lead);
    $this->append(clone $lead);
    $this->logger->debug('Lead "{name}" added (id={id})', [
      'name' => $lead->getName(),
      'id'   => $lead->getId(),
    ]);
  }

  /**
   * Save (add or update) lead
   *
   * @param $operation
   * @param \AmoCrm\Client\Object\Lead $lead
   * @throws \AmoCrm\Client\Exception\ResponseAggregatorException
   */
  protected function save($operation, Lead $lead) {
    if ($result = parent::_save('leads', $operation, [$lead->toArray()])) {
      $one_item = reset($result);
      $lead->setId($one_item['id']);
    }
  }

  /**
   * Update lead in the amoCRM database
   *
   * @param \AmoCrm\Client\Object\Lead $lead
   * @throws \AmoCrm\Client\Exception\ResponseAggregatorException
   */
  public function update(Lead $lead) {
    $this->save('update', $lead);
    $this->logger->debug('Lead "{name}" updated (id={id})', [
      'name' => $lead->getName(),
      'id'   => $lead->getId(),
    ]);
  }


}
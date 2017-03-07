<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 18.01.2017
 * Time: 18:30
 */

namespace AmoCrm\Client\Aggregator;


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
    if ($result = parent::_search('leads', $query)) {
      foreach ($result as $one_item) {
        $this->append(new Lead($one_item, $this->field_config));
      }
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get the lead from the amoCRM using ID
   *
   * @param string $id amoCRM lead id
   * @return bool
   */
  public function get($id) {
    $this->clear();
    if ($result = parent::_get('leads', $id)) {
      foreach ($result as $one_item) {
        $this->append(new Lead($one_item, $this->field_config));
      }
      return TRUE;
    }
    return FALSE;
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
    $this->logger->info('Lead "{name}" added (id={id})', [
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
    $this->logger->info('Lead "{name}" updated (id={id})', [
      'name' => $lead->getName(),
      'id'   => $lead->getId(),
    ]);
  }


}
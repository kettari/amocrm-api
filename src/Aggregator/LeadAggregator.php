<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 18.01.2017
 * Time: 18:30
 */

namespace AmoCrm\Client\Aggregator;


use AmoCrm\Client\Exception\QueryAggregatorException;
use AmoCrm\Client\Exception\ResponseAggregatorException;
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
    if (empty($query)) {
      throw new QueryAggregatorException('Query is empty');
    }

    $this->clear();

    // Search
    if ($response_array = $this->request
      ->setMethod('/private/api/v2/json/leads/list')
      ->setQuery($query)
      ->get()
    ) {

      if (isset($response_array['response']['leads']) && is_array($response_array['response']['leads'])) {
        foreach ($response_array['response']['leads'] as $one_item) {
          $this->append(new Lead($one_item, $this->field_config));
        }
      }
      else {
        throw new ResponseAggregatorException('Bad leads response structure');
      }

      $this->logger->info('Search in leads for query "{query}" returned {results_count} result(s)', [
        'query'         => $query,
        'results_count' => $this->count(),
      ]);
      return TRUE;
    }

    $this->logger->info('Search in leads for query "{query}" returned empty result', ['query' => $query]);
    return FALSE;
  }

  /**
   * Add lead to the amoCRM database
   *
   * @param \AmoCrm\Client\Object\Lead $lead
   * @throws \AmoCrm\Client\Exception\ResponseAggregatorException
   */
  public function add(Lead $lead) {
    $this->save('add', $lead);
    $this->append(clone $lead);
  }

  /**
   * Save (add or update) lead
   *
   * @param $operation
   * @param \AmoCrm\Client\Object\Lead $lead
   * @throws \AmoCrm\Client\Exception\ResponseAggregatorException
   */
  protected function save($operation, Lead $lead) {
    $request = [
      'request' => [
        'leads' => [
          $operation => [
            $lead->getArray(),
          ],
        ],
      ],
    ];

    if ($response_array = $this->request
      ->setMethod('/private/api/v2/json/leads/set')
      ->setQuery('')
      ->post($request)
    ) {

      if (isset($response_array['response']['leads'][$operation]) && is_array($response_array['response']['leads'][$operation])) {
        $one_item = reset($response_array['response']['leads'][$operation]);
        $lead->setId($one_item['id']);
      }
      else {
        throw new ResponseAggregatorException('Bad leads response structure');
      }

      $this->logger->info(sprintf('Lead "{lead_name}" (id={lead_id}) %sed', $operation),
        [
          'lead_name' => $lead->getName(),
          'lead_id'   => $lead->getId(),
        ]);

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
  }


}
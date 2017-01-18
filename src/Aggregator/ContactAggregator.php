<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 18.01.2017
 * Time: 18:30
 */

namespace AmoCrm\Client\Aggregator;


use AmoCrm\Client\Exception\ResponseAggregatorException;
use AmoCrm\Client\Object\Contact;

class ContactAggregator extends GeneralAggregator {

  /**
   * Search contacts for substring. Searches in name, phone, email
   * and custom fields. Does not search in notes and tasks.
   *
   * @param $query
   * @return bool
   * @throws \AmoCrm\Client\Exception\ResponseAggregatorException
   */
  public function search($query) {
    if ($response_array = $this->request
      ->setMethod('/private/api/v2/json/contacts/list')
      ->setQuery($query)
      ->get()) {

      if (isset($response_array['response']['contacts']) && is_array($response_array['response']['contacts'])) {
        foreach ($response_array['response']['contacts'] as $one_item) {
          $this->items->append(new Contact($one_item, $this->field_config));
        }
      }
      else {
        throw new ResponseAggregatorException('Bad contacts response structure');
      }

      $this->logger->info('Search in contacts for "{query}" returned {results_count} result(s)', ['query' => $query, 'results_count' => $this->items->count()]);
      return TRUE;
    }

    $this->logger->info('Search in contacts for "{query}" returned empty result', ['query' => $query]);
    return FALSE;
  }

}
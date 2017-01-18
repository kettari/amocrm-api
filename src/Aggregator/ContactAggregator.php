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
    if (empty($query)) {
      throw new QueryAggregatorException('Query is empty');
    }

    $this->clear();

    // Search
    if ($response_array = $this->request
      ->setMethod('/private/api/v2/json/contacts/list')
      ->setQuery($query)
      ->get()
    ) {

      if (isset($response_array['response']['contacts']) && is_array($response_array['response']['contacts'])) {
        foreach ($response_array['response']['contacts'] as $one_item) {
          $this->append(new Contact($one_item, $this->field_config));
        }
      }
      else {
        throw new ResponseAggregatorException('Bad contacts response structure');
      }

      $this->logger->info('Search in contacts for query "{query}" returned {results_count} result(s)', [
        'query'         => $query,
        'results_count' => $this->count(),
      ]);
      return TRUE;
    }

    $this->logger->info('Search in contacts for query "{query}" returned empty result', ['query' => $query]);
    return FALSE;
  }

  /**
   * Add contact to the amoCRM database
   *
   * @param \AmoCrm\Client\Object\Contact $contact
   * @throws \AmoCrm\Client\Exception\ResponseAggregatorException
   */
  public function add(Contact $contact) {
    $this->save('add', $contact);
    $this->append(clone $contact);
  }

  /**
   * Save (add or update) contact
   *
   * @param $operation
   * @param \AmoCrm\Client\Object\Contact $contact
   * @throws \AmoCrm\Client\Exception\ResponseAggregatorException
   */
  protected function save($operation, Contact $contact) {
    $request = [
      'request' => [
        'contacts' => [
          $operation => [
            $contact->getArray(),
          ],
        ],
      ],
    ];

    if ($response_array = $this->request
      ->setMethod('/private/api/v2/json/contacts/set')
      ->setQuery('')
      ->post($request)
    ) {

      if (isset($response_array['response']['contacts'][$operation]) && is_array($response_array['response']['contacts'][$operation])) {
        $one_item = reset($response_array['response']['contacts'][$operation]);
        $contact->setId($one_item['id']);
      }
      else {
        throw new ResponseAggregatorException('Bad contacts response structure');
      }

      $this->logger->info(sprintf('Contact "{contact_name}" (id={contact_id}) %sed', $operation),
        [
          'contact_name' => $contact->getName(),
          'contact_id'   => $contact->getId(),
        ]);

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
  }


}
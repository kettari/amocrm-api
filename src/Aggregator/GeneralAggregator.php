<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 18.01.2017
 * Time: 18:43
 */

namespace AmoCrm\Client\Aggregator;


use AmoCrm\Client\CustomField\FieldConfig;
use AmoCrm\Client\Exception\IdentifierAggregatorException;
use AmoCrm\Client\Exception\ResponseAggregatorException;
use AmoCrm\Client\Exception\UnknownEntityAggregatorException;
use AmoCrm\Client\Exception\UnknownOperationAggregatorException;
use AmoCrm\Client\Request;
use ArrayObject;
use Monolog\Logger;

abstract class GeneralAggregator extends ArrayObject {

  /**
   * Monolog logger
   *
   * @var Logger
   */
  protected $logger;

  /**
   * @var FieldConfig
   */
  protected $field_config;

  /**
   * @var \AmoCrm\Client\Request
   */
  protected $request;

  /**
   * Loggable constructor.
   *
   * @param \Monolog\Logger $logger
   * @param \AmoCrm\Client\CustomField\FieldConfig $field_config
   * @param \AmoCrm\Client\Request $request
   */
  public function __construct(Logger $logger, FieldConfig $field_config, Request $request) {
    $this->logger = $logger;
    $this->field_config = $field_config;
    $this->request = $request;
  }

  /**
   * Clear items
   */
  public function clear() {
    $iterator = $this->getIterator();
    foreach ($iterator as $key => $item) {
      $iterator->offsetUnset($key);
    }
  }

  /**
   * Search for entities
   *
   * @param $query
   * @return mixed
   */
  abstract public function search($query);

  /**
   * Get entity by ID
   *
   * @param string $id
   * @return mixed
   */
  abstract public function get($id);

  /**
   * Get entity by ID
   *
   * @param string $entities Entities: 'contacts' or 'leads'
   * @param string $id
   * @return array|null Array of entities or NULL
   * @throws \AmoCrm\Client\Exception\IdentifierAggregatorException
   * @throws \AmoCrm\Client\Exception\ResponseAggregatorException
   * @throws \AmoCrm\Client\Exception\UnknownEntityAggregatorException
   */
  protected function _get($entities, $id) {
    if (empty($id)) {
      throw new IdentifierAggregatorException('ID is empty');
    }
    if (!in_array($entities, ['contacts', 'leads', 'notes'])) {
      throw new UnknownEntityAggregatorException(sprintf('Entities "%s" is not supported',
        $entities));
    }

    // Search
    if ($response_array = $this->request->setMethod(sprintf('/private/api/v2/json/%s/list',
      $entities))
      ->setQuery(NULL)
      ->setId($id)
      ->get()
    ) {
      if (isset($response_array['response'][$entities]) &&
        is_array($response_array['response'][$entities])
      ) {
        $this->logger->debug(sprintf('Search in %s for ID "{id}" returned {results_count} result(s)',
          $entities), [
          'id'            => $id,
          'results_count' => count($response_array['response'][$entities]),
        ]);

        return $response_array['response'][$entities];
      } else {
        throw new ResponseAggregatorException(sprintf('Bad %s response structure',
          $entities));
      }
    }

    $this->logger->debug(sprintf('Search in %s for ID "{id}" returned empty result',
      $entities), ['id' => $id]);

    return NULL;
  }

  /**
   * Get entity by ID
   *
   * @param string $entity_type Entities: 'contacts' or 'leads'
   * @param string $query
   * @param int $page_size
   * @param callable $callback
   * @return array|null Array of entities or NULL
   * @throws \AmoCrm\Client\Exception\IdentifierAggregatorException
   * @throws \AmoCrm\Client\Exception\ResponseAggregatorException
   * @throws \AmoCrm\Client\Exception\UnknownEntityAggregatorException
   */
  protected function _load($entity_type, $query = NULL, $page_size = 50, callable $callback = NULL) {
    if (!in_array($entity_type, ['contacts', 'leads'])) {
      throw new UnknownEntityAggregatorException(sprintf('Entities "%s" is not supported',
        $entity_type));
    }

    // Configure request
    $current_page = 0;
    $this->request->setMethod(sprintf('/private/api/v2/json/%s/list',
      $entity_type))
      ->setPageSize($page_size)
      ->setQuery($query)
      ->setId(NULL);

    // Search
    $batch = [];
    do {
      $part = [];
      $response = $this->request->setPageNumber(++$current_page)
        ->get();
      if (isset($response['response'][$entity_type]) &&
        is_array($response['response'][$entity_type])
      ) {
        $part = $response['response'][$entity_type];
        // Add log
        $this->logger->debug(sprintf('Loaded {results_count} %s (page {current_page})',
          $entity_type), [
          'results_count' => count($part),
          'query'         => $query,
          'current_page'  => $current_page,
        ]);

        $batch = array_merge($batch, $part);
        if (!is_null($callback)) {
          call_user_func_array($callback,
            ['status' => 'loading', 'data' => $part]);
        }
      }
    } while (count($part) == $page_size);

    // Add log
    $this->logger->debug(sprintf('Total loaded {results_count} %s in {total_pages} requests',
      $entity_type), [
      'results_count' => count($batch),
      'query'         => $query,
      'total_pages'   => $current_page,
    ]);
    if (!is_null($callback)) {
      call_user_func_array($callback,
        ['status' => 'loaded', 'data' => $batch]);
    }

    return $batch;
  }

  /**
   * Save (add or update) entities
   *
   * @param string $entities Entities: 'contacts' or 'leads'
   * @param string $operation Operation: 'add' or 'update'
   * @param array $rows Data rows to be sent to amoCRM
   * @return array Server response
   * @throws \AmoCrm\Client\Exception\ResponseAggregatorException
   * @throws \AmoCrm\Client\Exception\UnknownEntityAggregatorException
   * @throws \AmoCrm\Client\Exception\UnknownOperationAggregatorException
   */
  protected function _save($entities, $operation, $rows) {
    if (!in_array($entities, ['contacts', 'leads', 'notes'])) {
      throw new UnknownEntityAggregatorException(sprintf('Entities "%s" is not supported',
        $entities));
    }
    if (!in_array($operation, ['add', 'update'])) {
      throw new UnknownOperationAggregatorException(sprintf('Operation "%s" is not supported',
        $operation));
    }

    $request = [
      'request' => [
        $entities => [
          $operation => $rows,
        ],
      ],
    ];

    if ($response_array = $this->request->setMethod(sprintf('/private/api/v2/json/%s/set',
      $entities))
      ->setQuery(NULL)
      ->setId(NULL)
      ->post($request)
    ) {
      if (isset($response_array['response'][$entities][$operation]) &&
        is_array($response_array['response'][$entities][$operation])
      ) {
        $this->logger->debug('Entities saved: operation={operation}, count={count}',
          [
            'operation' => $operation,
            'count'     => count($rows),
          ]);

        return $response_array['response'][$entities][$operation];
      } else {
        throw new ResponseAggregatorException(sprintf('Bad %s response structure',
          $entities));
      }
    }

    throw new ResponseAggregatorException(sprintf('Bad %s response: empty or NULL',
      $entities));
  }

}
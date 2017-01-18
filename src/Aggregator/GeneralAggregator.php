<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 18.01.2017
 * Time: 18:43
 */

namespace AmoCrm\Client\Aggregator;


use AmoCrm\Client\CustomField\FieldConfig;
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
   * Search for entities in the amoCRM
   *
   * @param $query
   * @return mixed
   */
  abstract public function search($query);

}
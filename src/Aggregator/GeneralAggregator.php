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
use Monolog\Logger;

class GeneralAggregator {

  /**
   * @var \ArrayIterator
   */
  protected $items;

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
    $this->items = new \ArrayIterator();
  }

}
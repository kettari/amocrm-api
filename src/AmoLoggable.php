<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 22.11.2016
 * Time: 13:31
 */

namespace AmoCrm\Api;

use Monolog\Logger;

class AmoLoggable {

  /**
   * Monolog logger
   *
   * @var Logger
   */
  protected $logger;

  /**
   * AmoLoggable constructor.
   *
   * @param \Monolog\Logger $logger
   */
  public function __construct(Logger $logger) {
    $this->logger = $logger;
  }
}
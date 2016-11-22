<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 22.11.2016
 * Time: 13:31
 */

namespace AmoCrm\Api;

use Monolog\Handler\LogEntriesHandler;
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
   * @param string $logentries_token
   */
  public function __construct($logentries_token) {
    $this->logger = new Logger('amocrm_api');
    $this->logger->pushHandler(new LogEntriesHandler($logentries_token));
  }
}
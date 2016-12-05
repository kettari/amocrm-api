<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 17.08.2016
 * Time: 16:42
 */

namespace AmoCrm\Api;
use Monolog\Logger;

/**
 * Class AmoBurstController
 *
 * @package AmoCrm\Api
 */
class AmoBurstController extends AmoLoggable {

  /**
   * @var null
   */
  private static $_instance = NULL;

  /**
   * Number of requests allowed to the API per second
   *
   * @var int
   */
  protected $frequency_allowed = 1;

  /**
   * Period of time to analyze burst in seconds
   * 
   * @var int
   */
  protected $burst_period = 3;

  /**
   * Relax time: how many seconds we wait to free the queue and calm 
   * amoCRM watchdog
   * 
   * @var int
   */
  protected $relax_time = 30;

  /**
   * @var array
   */
  protected $requests = [];

  /**
   * @inheritDoc
   */
  private function __clone() {
  }

  /**
   * Return instance of this singleton
   *
   * @param Logger $logger
   * @return \AmoCrm\Api\AmoBurstController|null
   */
  public static function getInstance($logger) {
    if (is_null(self::$_instance)) {
      self::$_instance = new AmoBurstController($logger);
    }
    return self::$_instance;
  }

  /**
   * Is request allowed?
   *
   * @return bool
   */
  public function isRequestAllowed() {
    // Analyze the burst
    $now = microtime(TRUE);
    $cutoff = $now - $this->burst_period;
    foreach ($this->requests as $key => $rq) {
      if ($rq < $cutoff) {
        unset($this->requests[$key]);
      }
    }
    return ((count($this->requests) / $this->burst_period) < $this->frequency_allowed);
  }

  /**
   * When API request is sent this method must be called
   */
  public function requestSent() {
    $this->requests[] = microtime(TRUE);

    // Add log message
    /*TL::log(TL::LOG_LEVEL_DEBUG, sprintf('Burst queue length = %d', count($this->requests)),
      __CLASS__ . '->' . __FUNCTION__);*/
  }

  /**
   * Wait until request is allowed
   *
   * @return bool
   */
  public function wait() {
    $wait_start = microtime(TRUE);
    while (!$this->isRequestAllowed()) {
      $now = microtime(TRUE);
      if (($now - $wait_start) > $this->relax_time) {
        // Add log message
        $this->logger->error('Burst control: relax time exceeded', ['source' => __CLASS__ . '->' . __FUNCTION__]);

        return FALSE;
      }
      usleep(0.1 * 1000000);
    }
    return TRUE;
  }

}
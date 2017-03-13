<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 13.03.2017
 * Time: 14:14
 */

namespace AmoCrm\Client;

/**
 * Class ThrottleSingleton
 *
 * @package AmoCrm\Client
 */
class ThrottleSingleton {

  /**
   * @var null
   */
  private static $instance = NULL;

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
   * @var int
   */
  protected $waiting_cycles = 0;

  /**
   * @var int
   */
  protected $queue_length_peak = 0;

  /**
   * @inheritDoc
   */
  private function __construct() {
  }

  /**
   * @inheritDoc
   */
  private function __clone() {
  }

  /**
   * Return instance of this singleton
   *
   * @return ThrottleSingleton
   */
  public static function getInstance() {
    if (is_null(self::$instance)) {
      self::$instance = new ThrottleSingleton();
    }

    return self::$instance;
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

    return ((count($this->requests) / $this->burst_period) <
      $this->frequency_allowed);
  }

  /**
   * When API request is sent this method must be called
   */
  public function requestSent() {
    $this->requests[] = microtime(TRUE);

    // Collect statistics
    if (count($this->requests) > $this->queue_length_peak) {
      $this->queue_length_peak = count($this->requests);
    }
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
        // Relax time exceeded, request still not allowed
        return FALSE;
      }
      usleep(0.1 * 1000000);

      // Collect statistics
      $this->waiting_cycles++;
    }

    return TRUE;
  }

  /**
   * Returns count of waiting cycles.
   *
   * @return int
   */
  public function getWaitingCycles() {
    return $this->waiting_cycles;
  }

  /**
   * Returns queue lenth peak.
   *
   * @return int
   */
  public function getQueueLengthPeak() {
    return $this->queue_length_peak;
  }

}
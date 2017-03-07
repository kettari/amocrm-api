<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 18.01.2017
 * Time: 18:21
 */

namespace AmoCrm\Client;

use AmoCrm\Client\Aggregator\ContactAggregator;
use AmoCrm\Client\Aggregator\LeadAggregator;
use AmoCrm\Client\Aggregator\NoteAggregator;
use AmoCrm\Client\CustomField\FieldConfig;
use Monolog\Logger;

class Api {

  /**
   * @var Logger
   */
  protected $logger;

  /**
   * @var string
   */
  protected $subdomain;

  /**
   * @var string
   */
  protected $login;

  /**
   * @var string
   */
  protected $api_hash;

  /**
   * Base amoCRM url with '%s' for subdomain
   *
   * @var string
   */
  protected $base_url = 'https://%s.amocrm.ru';

  /**
   * @var FieldConfig
   */
  protected $field_config;

  /**
   * Api constructor.
   *
   * @param \Monolog\Logger $logger
   * @param string $subdomain
   * @param string $login
   * @param string $api_hash
   * @param \AmoCrm\Client\CustomField\FieldConfig $field_config
   */
  public function __construct(Logger $logger, $subdomain, $login, $api_hash, FieldConfig $field_config) {
    $this->logger = $logger;
    $this->subdomain = $subdomain;
    $this->login = $login;
    $this->api_hash = $api_hash;
    $this->field_config = $field_config;
  }

  /**
   * @return string
   */
  public function getBaseUrl() {
    return $this->base_url;
  }

  /**
   * @param string $base_url Base amoCRM url with '%s' for subdomain
   * @return $this
   */
  public function setBaseUrl($base_url) {
    $this->base_url = $base_url;
    return $this;
  }

  /**
   * Create contacts aggregator object
   *
   * @return ContactAggregator
   */
  public function getContactAggregator() {
    $request = new Request($this->logger);
    $request
      ->setUrl(sprintf($this->base_url, $this->subdomain))
      ->setLogin($this->login)
      ->setApiHash($this->api_hash);
    return new ContactAggregator($this->logger, $this->field_config, $request);
  }

  /**
   * Create leads aggregator object
   *
   * @return LeadAggregator
   */
  public function getLeadAggregator() {
    $request = new Request($this->logger);
    $request
      ->setUrl(sprintf($this->base_url, $this->subdomain))
      ->setLogin($this->login)
      ->setApiHash($this->api_hash);
    return new LeadAggregator($this->logger, $this->field_config, $request);
  }

  /**
   * Create notes aggregator object
   *
   * @return NoteAggregator
   */
  public function getNoteAggregator() {
    $request = new Request($this->logger);
    $request
      ->setUrl(sprintf($this->base_url, $this->subdomain))
      ->setLogin($this->login)
      ->setApiHash($this->api_hash);
    return new NoteAggregator($this->logger, $this->field_config, $request);
  }

}
<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 26.07.2016
 * Time: 13:33
 */

namespace AmoCrm\Api;

use AmoCrm\Api\Objects\AmoContactObject;

use AmoCrm\Api\Objects\AmoLinkObject;


/**
 * Class AmoContactController
 *
 * @package AmoCrm\Api
 */
class AmoContactController extends AmoEntityController {

  /**
   * Flag to indicate we shall use cache
   *
   * @var bool
   */
  protected $use_cache = FALSE;

  /**
   * Alias for find() method.
   *
   * @return bool
   * @param $query
   */
  public function exists($query) {
    return parent::_exists('contacts', $query);
  }

  /**
   * Find contacts in the AmoCRM. Filtered with query substring.
   *
   * @param string $query
   * @param int $limit_rows
   * @return \AmoCrm\Api\Response\AmoBaseResponse
   */
  public function find($query, $limit_rows = 50) {
    $this->setFilterQuery($query);
    return parent::_find('contacts', $limit_rows);
  }

  /**
   * Get contact by its id
   *
   * @param $id
   * @return \AmoCrm\Api\Response\AmoBaseResponse
   */
  public function get($id) {
    $this->setFilterIds([$id]);
    return parent::_find('contacts');
  }

  /**
   * Create multiple leads in the AmoCRM
   *
   * @param array $contacts
   * @return $this
   */
  public function addMultiple(array $contacts) {
    // Prepare data for request
    $data = [];
    /** @var AmoContactObject $contact */
    foreach ($contacts as $contact) {
      if (!is_null($contact->getId())) {
        $data['request']['contacts']['update'][] = $contact->getArray();
      }
      else {
        $data['request']['contacts']['add'][] = $contact->getArray();
      }
    }

    $this->buffer = array_merge($this->buffer, $data);

    // Add debug log message
    $this->logger->debug(sprintf('Contacts addMultiple(): <pre>%s</pre>',
      print_r($this->buffer, TRUE)), ['source' => __CLASS__ . '->' . __FUNCTION__]);

    return $this;
  }

  /**
   * @inheritdoc
   */
  public function execute() {
    return parent::_execute('contacts');
  }

  /**
   * Load all contacts from the amoCRM
   *
   * @param bool $use_cache
   * @return mixed
   */
  public function rosterAll($use_cache = TRUE) {
    $start = microtime(TRUE);

    if (($cache_expired = $this->_cacheExpired()) || !$use_cache) {
      // Save zero server time
      // TODO: Save timestamp somewhere
      /*\Drupal::configFactory()->getEditable('telebot.settings')
        ->set('amo.contact_cache_timestamp', 0)
        ->set('amo.contact_cache_next_full_load_timestamp', time() + (60 * 30))
        ->save();*/

      if ($cache_expired) {
        $this->logger->notice('Amo contacts cache expired', ['source' => __CLASS__ . '->' . __FUNCTION__]);
      }
    }

    // Load contacts from the amoCRM
    $contacts_original = $this->_loadContactsFromAmo();

    // Load contacts from the cache
    if ($use_cache) {
      // TODO: Load raw data from the cache
      $raw_data = '';
      /*$raw_data = \Drupal::config('telebot.settings')
        ->get('amo.contact_cache_data');*/
      if (strlen($raw_data) == 0) {
        $raw_data = serialize([]);
      }

      // Add log
      $this->logger->info('Loading amo contacts using the cache', ['source' => __CLASS__ . '->' . __FUNCTION__]);
    }
    else {
      // Clear the cache
      $raw_data = serialize([]);

      // Add log
      $this->logger->info('Loading amo contacts NOT using the cache at all', ['source' => __CLASS__ . '->' . __FUNCTION__]);
    }
    $contacts_cached = unserialize($raw_data);

    // Mix 'em
    /** @var AmoContactObject $contact_origin */
    foreach ($contacts_original as $contact_origin) {
      $contacts_cached[$contact_origin->getId()] = $contact_origin;
    }

    // Save the cache
    // TODO: Save the cache
    /*\Drupal::configFactory()->getEditable('telebot.settings')
      ->set('amo.contact_cache_data', serialize($contacts_cached))
      ->save();*/

    // Add log message
    $time_elapsed_secs = microtime(TRUE) - $start;
    $this->logger->info(sprintf('Roster all contacts (%d items) from amoCRM execution time: %.2f seconds',
      count($contacts_cached), $time_elapsed_secs), ['source' => __CLASS__ . '->' . __FUNCTION__]);

    return $contacts_cached;
  }

  /**
   * Check if local cache expired
   *
   * @return bool
   */
  private function _cacheExpired() {
    $contact_cache_next_full_load_timestamp = 0;
    // TODO: Find if cache has expired
    /*$contact_cache_next_full_load_timestamp = \Drupal::config('telebot.settings')
      ->get('amo.contact_cache_next_full_load_timestamp');*/
    if (time() >= $contact_cache_next_full_load_timestamp)  {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * Load contacts from the amoCRM
   *
   * @return array|bool
   */
  private function _loadContactsFromAmo() {
    $limit_rows = 500;
    $limit_offset = 0;
    $contacts = [];

    // YES use the cache
    $this->use_cache = TRUE;

    // Download contacts until all done
    do {
      $response = $this->roster($limit_rows, $limit_offset);
      // Check for error
      if ($response->isErrorFlag()) {
        // Add log message
        $this->logger->error(sprintf('AmoCRM returned error. Message: %s',
          $response->getErrorMessage()), ['source' => __CLASS__ . '->' . __FUNCTION__]);
        return FALSE;
      }
      else {
        // Process payload
        $payload = $response->getPayload();
        if (is_array($payload) && (count($payload) > 0)) {
          // Add contacts to the storage
          $contacts = array_merge($contacts, $payload);
          $limit_offset += count($payload);
        }
        // Process server time
        $effective_server_time = $response->getServerTime();
      }
    }
    while (count($payload) == $limit_rows);

    // Switch off cache using
    $this->use_cache = FALSE;
    // Save server time
    if ($effective_server_time > 0) {
      // TODO: Save server time
      /*\Drupal::configFactory()->getEditable('telebot.settings')
        ->set('amo.contact_cache_timestamp', $effective_server_time)
        ->save();*/
    }

    return $this->_indexContacts($contacts);
  }

  /**
   * Load list of contacts
   *
   * @param int $limit_rows
   * @param int $limit_offset
   * @return \AmoCrm\Api\Response\AmoBaseResponse
   */
  public function roster($limit_rows = 500, $limit_offset = 0) {
    return parent::_find('contacts', $limit_rows, $limit_offset);
  }

  /**
   * Add index
   *
   * @param $arr
   * @return array
   */
  private function _indexContacts($arr) {
    $indexed_arr = [];
    /** @var AmoContactObject $contact */
    foreach ($arr as $contact) {
      $indexed_arr[$contact->getId()] = $contact;
    }
    return $indexed_arr;
  }

  /**
   * @inheritdoc
   */
  protected function setCurlOptions(&$handler) {
    parent::setCurlOptions($handler);

    // Add If-modified-since header if we use the cache
    if ($this->use_cache) {
      // Get config values for ticket templates
      // TODO: Get config values for ticket templates
      $timestamp = 0;
      /*$timestamp = \Drupal::config('telebot.settings')
        ->get('amo.contact_cache_timestamp');*/
      curl_setopt($handler, CURLOPT_HTTPHEADER, [sprintf('If-Modified-Since: %s', date('r', $timestamp))]);
    }
  }

  /**
   * Load all leads for specified contacts
   *
   * @param integer $lead_id
   * @return array|bool
   */
  public function rosterForLead($lead_id) {
    // Load links
    $links = $this->_loadLinks($lead_id);
    if (FALSE === $links) {
      $this->logger->error(sprintf('Error rostering contacts for the lead (id = %d)',
        $lead_id), ['source' => __CLASS__ . '->' . __FUNCTION__]);

      return FALSE;
    }

    // Process links payload
    $contact_ids = [];
    /** @var AmoLinkObject $link */
    foreach ($links as $link) {
      $contact_ids[] = $link->getContactId();
    }

    // Load contacts
    if (count($contact_ids) > 0) {
      $leads = $this->_loadContacts($contact_ids);
      if (FALSE === $leads) {
        $this->logger->error(sprintf('Error rostering contacts by ids for the lead (id = %d)',
          $lead_id), ['source' => __CLASS__ . '->' . __FUNCTION__]);

        return FALSE;
      }
      return $leads;
    }
    else {
      $this->logger->error(sprintf('No contacts found for the lead (id = %d)',
        $lead_id), ['source' => __CLASS__ . '->' . __FUNCTION__]);

      return [];
    }
  }

  /**
   * Load links from the amoCRM
   *
   * @param $contact_id
   * @return array|bool
   */
  private function _loadLinks($contact_id) {
    // Set the limits
    $limit_rows = 500;
    $limit_offset = 0;

    // Prepare the link
    $link = $this->getBaseLink() . '/private/api/v2/json/contacts/links';
    // Add auth
    $link = $link . sprintf('?USER_LOGIN=%s&USER_HASH=%s',
        $this->user_login, $this->api_hash);
    // Add parameters
    $link .= sprintf('&limit_rows=%d', $limit_rows);
    $link .= sprintf('&deals_link[]=%d', $contact_id);

    // Download records until all done
    $data = [];
    do {
      $offset_link = $link . sprintf('&limit_offset=%d', $limit_offset);
      $response = $this->sendRequest('GET', $offset_link);
      // Check for error
      if ($response->isErrorFlag()) {
        // Add log message
        $this->logger->error(sprintf('AmoCRM returned error. Message: %s',
          $response->getErrorMessage()), ['source' => __CLASS__ . '->' . __FUNCTION__]);

        return FALSE;
      }
      else {
        $payload = $response->getPayload();
        if (is_array($payload) && (count($payload) > 0)) {
          // Add records to the storage
          $data = array_merge($data, $payload);
          $limit_offset += count($payload);
        }
      }
    }
    while (count($payload) == $limit_rows);

    return $data;
  }

  /**
   * Load leads from the amoCRM by ids
   *
   * @param array $ids
   * @return array|bool
   */
  private function _loadContacts(array $ids) {
    // Set the limits
    $limit_rows = 500;
    $limit_offset = 0;

    // Prepare the link
    $link = $this->getBaseLink() . sprintf('/private/api/v2/json/%s/list',
        'contacts');
    // Add auth
    $link = $link . sprintf('?USER_LOGIN=%s&USER_HASH=%s',
        $this->user_login, $this->api_hash);
    // Add parameters
    $link .= sprintf('&limit_rows=%d', $limit_rows);
    foreach ($ids as $item) {
      $link .= sprintf('&id[]=%d', $item);
    }

    // Download records until all done
    $data = [];
    do {
      $offset_link = $link . sprintf('&limit_offset=%d', $limit_offset);
      $response = $this->sendRequest('GET', $offset_link);
      // Check for error
      if ($response->isErrorFlag()) {
        // Add log message
        $this->logger->error(sprintf('AmoCRM returned error. Message: %s',
          $response->getErrorMessage()), ['source' => __CLASS__ . '->' . __FUNCTION__]);

        return FALSE;
      }
      else {
        $payload = $response->getPayload();
        if (is_array($payload) && (count($payload) > 0)) {
          // Add records to the storage
          $data = array_merge($data, $payload);
          $limit_offset += count($payload);
        }
      }
    }
    while (count($payload) == $limit_rows);

    return $data;
  }
}
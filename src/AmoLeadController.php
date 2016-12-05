<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 26.07.2016
 * Time: 13:33
 */

namespace AmoCrm\Api;

use AmoCrm\Api\Object\AmoLeadObject;
use AmoCrm\Api\Object\AmoLinkObject;

/**
 * Class AmoLeadController
 *
 * @package AmoCrm\Api
 */
class AmoLeadController extends AmoEntityController {

  /**
   * Alias for find() method.
   *
   * @return bool
   * @param $query
   */
  public function exists($query) {
    return parent::_exists('leads', $query);
  }

  /**
   * Find leads in the AmoCRM. Filtered with query substring.
   *
   * @param string $query
   * @param int $limit_rows
   * @return \AmoCrm\Api\Response\AmoBaseResponse
   */
  public function find($query, $limit_rows = 50) {
    $this->setFilterQuery($query);
    return parent::_find('leads', $limit_rows);
  }

  /**
   * Get lead by its id
   *
   * @param $id
   * @return \AmoCrm\Api\Response\AmoBaseResponse
   */
  public function get($id) {
    $this->setFilterIds([$id]);
    return parent::_find('leads');
  }

  /**
   * Create multiple leads in the AmoCRM
   *
   * @param array $leads
   * @return $this
   */
  public function addMultiple(array $leads) {
    // Prepare data for request
    $data = [];
    /** @var AmoLeadObject $lead */
    foreach ($leads as $lead) {
      if (!($lead instanceof AmoLeadObject)) {
        $this->logger->error('Expected AmoLeadObject, got {unexpected_class}',
          [
            'unexpected_class' => get_class($lead),
            'source'           => __CLASS__ . '->' . __FUNCTION__,
          ]);
        continue;
      }

      if (!is_null($lead->getId())) {
        $data['request']['leads']['update'][] = $lead->getArray();
      }
      else {
        $data['request']['leads']['add'][] = $lead->getArray();
      }
    }

    $this->buffer = array_merge($this->buffer, $data);

    // Add debug log message
    /*$this->logger->debug('Leads addMultiple()',
      [
        'leads'  => $this->buffer,
        'source' => __CLASS__ . '->' . __FUNCTION__,
      ]);*/

    return $this;
  }

  /**
   * @inheritdoc
   */
  public function execute() {
    return parent::_execute('leads');
  }

  /**
   * Load all leads from the amoCRM
   *
   * @return mixed
   */
  public function rosterAll() {
    $start = microtime(TRUE);
    $limit_rows = 500;
    $limit_offset = 0;
    $leads_storage = [];

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
        $payload = $response->getPayload();
        if (is_array($payload) && (count($payload) > 0)) {
          // Add contacts to the storage
          $leads_storage = array_merge($leads_storage, $payload);
          $limit_offset += count($payload);
        }
      }
    } while (count($payload) == $limit_rows);

    // Add log message
    $time_elapsed_secs = microtime(TRUE) - $start;
    $this->logger->info(sprintf('Roster all leads from amoCRM execution time: %.2f seconds',
      $time_elapsed_secs), ['source' => __CLASS__ . '->' . __FUNCTION__]);

    return $leads_storage;
  }

  /**
   * Load list of leads
   *
   * @param int $limit_rows
   * @param int $limit_offset
   * @return \AmoCrm\Api\Response\AmoBaseResponse
   */
  public function roster($limit_rows = 500, $limit_offset = 0) {
    return parent::_find('leads', $limit_rows, $limit_offset);
  }

  /**
   * Load all leads for specified contacts
   *
   * @param $contact_id
   * @return array|bool
   */
  public function rosterForContact($contact_id) {
    // Load links
    $links = $this->_loadLinks($contact_id);
    if (FALSE === $links) {
      $this->logger->error(sprintf('Error rostering leads for the contact (id = %d)',
        $contact_id), ['source' => __CLASS__ . '->' . __FUNCTION__]);

      return FALSE;
    }

    // Process links payload
    $lead_ids = [];
    /** @var AmoLinkObject $link */
    foreach ($links as $link) {
      $lead_ids[] = $link->getLeadId();
    }

    // Load leads
    if (count($lead_ids) > 0) {
      $leads = $this->_loadLeads($lead_ids);
      if (FALSE === $leads) {
        $this->logger->error(sprintf('Error rostering leads by ids for the contact (id = %d)',
          $contact_id), ['source' => __CLASS__ . '->' . __FUNCTION__]);

        return FALSE;
      }
      return $leads;
    }
    else {
      $this->logger->notice(sprintf('No leads found for the contact (id = %d)',
        $contact_id), ['source' => __CLASS__ . '->' . __FUNCTION__]);

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
    $link .= sprintf('&contacts_link[]=%d', $contact_id);

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
    } while (count($payload) == $limit_rows);

    return $data;
  }

  /**
   * Load leads from the amoCRM by ids
   *
   * @param array $ids
   * @return array|bool
   */
  private function _loadLeads(array $ids) {
    // Set the limits
    $limit_rows = 500;
    $limit_offset = 0;

    // Prepare the link
    $link = $this->getBaseLink() . sprintf('/private/api/v2/json/%s/list',
        'leads');
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
    } while (count($payload) == $limit_rows);

    return $data;
  }
}
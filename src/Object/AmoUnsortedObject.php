<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 26.07.2016
 * Time: 16:18
 */

namespace AmoCrm\Api\Objects;

/**
 * Class AmoUnsortedObject
 *
 * @package AmoCrm\Api
 */
class AmoUnsortedObject {

  /**
   * Name of source
   *
   * @var string
   */
  protected $source;

  /**
   * Unique identifier of this case
   *
   * @var string
   */
  protected $source_uid;

  /**
   * Case data. No direct set, will prepare myself.
   *
   * @var array
   */
  protected $source_data;

  /**
   * Unix timestamp
   *
   * @var integer
   */
  protected $date_create;

  /**
   * source_data fields, used in preview
   */

  /**
   * AmoCRM form ID
   *
   * @var null
   */
  protected $form_id = 0;

  /**
   * Task type: 1 - meeting, 2 - follow up
   *
   * @var int
   */
  protected $form_type = 1;

  /**
   * @var null
   */
  protected $origin_ip = NULL;

  /**
   * @var null
   */

  protected $origin_datetime = NULL;
  /**
   * @var null
   */

  protected $origin_referrer = NULL;

  /**
   * Name of the site where this case originates from
   *
   * @var string
   */
  protected $from;

  /**
   * Name of the form
   *
   * @var
   */
  protected $form_name;

  /**
   * @var AmoContactObject
   */
  protected $contact = NULL;

  /**
   * @var AmoLeadObject
   */
  protected $lead = NULL;

  /**
   * AmoUnsortedObject constructor.
   *
   * @param integer $form_id
   * @param array $data
   */
  public function __construct($form_id, array $data) {
    $this->form_id = $form_id;
    foreach ($data as $key => $val) {
      if (property_exists(__CLASS__, $key)) {
        $this->$key = $val;
      }
    }
  }

  /**
   * Return array ready to be sent to AmoCRM
   */
  public function getArray() {

    // Prepare some variables
    $lead_array = (!is_null($this->lead)) ? $this->lead->getArray() : [];
    $contact_array = (!is_null($this->contact)) ? $this->contact->getArray() : [];

    $result = [
      'source'      => $this->source,
      'source_uid'  => $this->source_uid,
      'data'        => [
        'leads'     => [$lead_array],
        'contacts'  => [$contact_array],
        'companies' => [],
      ],
      'source_data' => $this->getSourceData(),
    ];
    return $result;
  }

  /**
   * @return array
   */
  protected function getSourceData() {

    // Prepare some variables
    $lead_name = (!is_null($this->lead)) ? $this->lead->getName() : '';
    $contact_name = (!is_null($this->contact)) ? $this->contact->getName() : '';
    $contact_phone = (!is_null($this->contact)) ? $this->contact->getPhones() : '';

    $i = 0;
    /** @noinspection PhpUnusedLocalVariableInspection */
    $result = [
      'data'      => [
        'name_' . $i++  => [
          'type'         => 'text',
          'id'           => 'name',
          'element_type' => '1',
          'name'         => 'О чём речь',
          'value'        => $lead_name,
        ],
        'name_' . $i++  => [
          'type'         => 'text',
          'id'           => 'name',
          'element_type' => '1',
          'name'         => 'Имя',
          'value'        => $contact_name,
        ],
        'phone_' . $i++ => [
          'type'         => 'multitext',
          'id'           => 0, // TODO: Put phone custom field ID here
          'element_type' => '1',
          'name'         => 'Телефон',
          'value'        => $contact_phone,
        ],
      ],
      'form_id'   => $this->form_id,
      'form_type' => $this->form_type,
      'origin'    => [
        'ip'       => $this->origin_ip,
        'datetime' => date('r', $this->origin_datetime),
        'referer'  => $this->origin_referrer,
      ],
      'date'      => $this->date_create,
      'from'      => $this->from,
      'form_name' => $this->form_name,
    ];
    return $result;
  }

}
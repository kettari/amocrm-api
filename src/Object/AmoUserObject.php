<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 16.08.2016
 * Time: 17:02
 */

namespace AmoCrm\Api\Objects;


/**
 * Class AmoUserObject
 *
 * @package AmoCrm\Api\Object
 */
class AmoUserObject {

  /**
   * @var integer
   */
  protected $id;

  /**
   * @var string
   */
  protected $mail_admin;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var string
   */
  //protected $last_name;

  /**
   * @var string
   */
  protected $login;

  /**
   * @var string
   */
  protected $photo_url;

  /**
   * @var string
   */
  protected $phone_number;

  /**
   * @var string
   */
  protected $language;

  /**
   * @var integer
   */
  protected $group_id;

  /**
   * @var string
   */
  protected $rights_lead_add;

  /**
   * @var string
   */
  protected $rights_lead_view;

  /**
   * @var string
   */
  protected $rights_lead_edit;

  /**
   * @var string
   */
  protected $rights_lead_delete;

  /**
   * @var string
   */
  protected $rights_lead_export;

  /**
   * @var string
   */
  protected $rights_contact_add;

  /**
   * @var string
   */
  protected $rights_contact_view;

  /**
   * @var string
   */
  protected $rights_contact_edit;

  /**
   * @var string
   */
  protected $rights_contact_delete;

  /**
   * @var string
   */
  protected $rights_contact_export;

  /**
   * @var string
   */
  protected $rights_company_add;

  /**
   * @var string
   */
  protected $rights_company_view;

  /**
   * @var string
   */
  protected $rights_company_edit;

  /**
   * @var string
   */
  protected $rights_company_delete;

  /**
   * @var string
   */
  protected $rights_company_export;

  /**
   * @var string
   */
  protected $unsorted_access;

  /**
   * @var string
   */
  protected $is_admin;

  /**
   * AmoUserObject constructor.
   *
   * @param array $data
   */
  public function __construct(array $data) {
    foreach ($data as $key => $val) {
      // Other properties
      if (property_exists(__CLASS__, $key)) {
        $this->$key = $val;
      }
    }
  }

  /**
   * @return int
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getMailAdmin() {
    return $this->mail_admin;
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @return string
   */
  public function getLogin() {
    return $this->login;
  }

  /**
   * @return string
   */
  public function getPhotoUrl() {
    return $this->photo_url;
  }

  /**
   * @return string
   */
  public function getPhoneNumber() {
    return $this->phone_number;
  }

  /**
   * @return string
   */
  public function getLanguage() {
    return $this->language;
  }

  /**
   * @return int
   */
  public function getGroupId() {
    return $this->group_id;
  }

  /**
   * @return string
   */
  public function getRightsLeadAdd() {
    return $this->rights_lead_add;
  }

  /**
   * @return string
   */
  public function getRightsLeadView() {
    return $this->rights_lead_view;
  }

  /**
   * @return string
   */
  public function getRightsLeadEdit() {
    return $this->rights_lead_edit;
  }

  /**
   * @return string
   */
  public function getRightsLeadDelete() {
    return $this->rights_lead_delete;
  }

  /**
   * @return string
   */
  public function getRightsLeadExport() {
    return $this->rights_lead_export;
  }

  /**
   * @return string
   */
  public function getRightsContactAdd() {
    return $this->rights_contact_add;
  }

  /**
   * @return string
   */
  public function getRightsContactView() {
    return $this->rights_contact_view;
  }

  /**
   * @return string
   */
  public function getRightsContactEdit() {
    return $this->rights_contact_edit;
  }

  /**
   * @return string
   */
  public function getRightsContactDelete() {
    return $this->rights_contact_delete;
  }

  /**
   * @return string
   */
  public function getRightsContactExport() {
    return $this->rights_contact_export;
  }

  /**
   * @return string
   */
  public function getRightsCompanyAdd() {
    return $this->rights_company_add;
  }

  /**
   * @return string
   */
  public function getRightsCompanyView() {
    return $this->rights_company_view;
  }

  /**
   * @return string
   */
  public function getRightsCompanyEdit() {
    return $this->rights_company_edit;
  }

  /**
   * @return string
   */
  public function getRightsCompanyDelete() {
    return $this->rights_company_delete;
  }

  /**
   * @return string
   */
  public function getRightsCompanyExport() {
    return $this->rights_company_export;
  }

  /**
   * @return string
   */
  public function getUnsortedAccess() {
    return $this->unsorted_access;
  }

  /**
   * @return string
   */
  public function getIsAdmin() {
    return $this->is_admin;
  }

}
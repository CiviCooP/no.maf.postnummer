<?php
/**
 * Class with extension specific util functions
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license AGPL-3.0
 */

class CRM_Postnummer_Utils {
  /**
   * Method to get norwegian county
   *
   * @param string $communityNumber
   * @return array|bool
   * @access public
   * @static
   */
  public static function getCountyWithCommunityNumber($communityNumber) {
    $stateProvince = array();
    $communityNumber = (string)$communityNumber;
    $communityCode = substr($communityNumber,0,2);
    $query = "SELECT id, name FROM civicrm_state_province WHERE country_id = %1
      AND abbreviation = %2";
    $params = array(
      1 => array(1161, "Integer"),
      2 => array($communityCode, "String")
    );
    $dao = CRM_Core_DAO::executeQuery($query, $params);
    if ($dao->fetch()) {
      $stateProvince['state_province_id'] = $dao->id;
      $stateProvince['state_province_name'] = $dao->name;
      return $stateProvince;
    } else {
      return FALSE;
    }
  }
}
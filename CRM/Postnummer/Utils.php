<?php
/**
 * Class with util functions for postnummer
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @date 17 Aug 2015
 * @license AGPL-3.0
 */
class CRM_Postnummer_Utils {

  /**
   * Function to get the county with postcode
   *
   * @param string $postCode
   * @return bool|string
   * @access public
   * @static
   */
  public static function getCountyWithPostcode($postCode) {
    if (empty($postCode)) {
      return FALSE;
    }
    $countyId = self::getCountyIdWithPostcode($postCode);
    if (!$countyId) {
      return FALSE;
    } else {
      return self::getCountyName($countyId);
    }
  }

  /**
   * Function to get the county id with postcode
   *
   * @param string $postCode
   * @return bool|string
   * @access public
   * @static
   */
  public function getCountyIdWithPostcode($postCode) {
    if (empty($postCode)) {
      return FALSE;
    }
    $query = 'SELECT community_number FROM civicrm_post_nummer WHERE post_code = %1';
    $params = array(1 => array($postCode, 'String'));
    $dao = CRM_Core_DAO::executeQuery($query, $params);
    if ($dao->fetch()) {
      return self::getCountyIdWithCountyCode($dao->community_number);
    } else {
      return FALSE;
    }
  }

  /**
   * Function to get the countyId with the county code
   *
   * @param $countyCode
   * @return bool|string
   * @access public
   * @static
   */
  public static function getCountyIdWithCountyCode($countyCode) {
    $config = CRM_Postnummer_Config::singleton();
    $counties = $config->getCounties();
    $countyId = (string) substr($countyCode, 0, 2);
    if (isset($counties[$countyId])) {
      return $countyId;
    } else {
      return FALSE;
    }
  }

  /**
   * Function to get the county name with county id
   *
   * @param string $countyId
   * @return bool|string
   * @access public
   * @static
   */
  public static function getCountyName($countyId) {
    $config = CRM_Postnummer_Config::singleton();
    $counties = $config->getCounties();
    if (isset($counties[$countyId])) {
      return $counties[$countyId];
    } else {
      return FALSE;
    }
  }
}
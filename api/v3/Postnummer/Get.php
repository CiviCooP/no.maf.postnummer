<?php

/**
 * PostNummer.Get API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_postnummer_get_spec(&$spec) {
  $spec['post_code']['api.required'] = 1;
}

/**
 * PostNummer.Get API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_postnummer_get($params) {
  if (!($params['post_code'])) {
    throw new API_Exception("Post Code is a mandatory param");
  }
  if (!CRM_Core_DAO::checkTableExists("civicrm_post_nummer")) {
    throw new API_Exception("No table civicrm_post_nummer found, can not use API PostCode Get");

  }
  $returnValues = array();
  $query = "SELECT * FROM civicrm_post_nummer WHERE post_code = %1";
  $dao = CRM_Core_DAO::executeQuery($query, array(1 => array($params['post_code'], "String")));
  if ($dao->fetch()) {
    $returnValue['post_code'] = $dao->post_code;
    $returnValue['post_city'] = $dao->post_city;
    $county = CRM_Postnummer_Utils::getCountyWithCommunityNumber($dao->community_number);
    if ($county != FALSE) {
      $returnValue['county_id'] = $county['state_province_id'];
    } else {
      $returnValue['county_id'] = "";
    }
    $returnValues[] = $returnValue;
  }
  return civicrm_api3_create_success($returnValues, $params, 'PostCode', 'Get');
}


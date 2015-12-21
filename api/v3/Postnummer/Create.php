<?php

/**
 * Postnummer.Create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_postnummer_create_spec(&$spec) {
  $spec['post_code']['api.required'] = 1;
}

/**
 * Postnummer.Create API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_postnummer_create($params) {
  if (!$params['post_code']) {
    throw new API_Exception('Mandatory parameter missing for Postnummer Create: post_code');
  }
  try {
    $queryParams = array();
    $queryFields = array();
    $queryParams[1] = array($params['post_code'], 'String');

    if ($params['post_city']) {
      $queryFields[] = 'post_city = %2';
      $queryParams[2] = array($params['post_city'], 'String');
    }

    if ($params['community_number']) {
      $queryFields[] = 'community_number = %3';
      $queryParams[3] = array($params['community_number'], 'String');
    }

    if ($params['community_name']) {
      $queryFields[] = 'community_name = %4';
      $queryParams[4] = array($params['community_name'], 'String');
    }

    if ($params['category']) {
      $queryFields[] = 'category = %5';
      $queryParams[5] = array($params['category'], 'String');
    }

    $query = 'INSERT INTO civicrm_post_nummer SET post_code = %1, '.implode(", ", $queryFields);
    CRM_Core_DAO::executeQuery($query, $queryParams);
  } catch (Exception $ex) {
    throw new API_Exception('Could not create postnummer with values: '.implode(";", $params)
      .'. Error message : '.$ex->getMessage());
  }
  return civicrm_api3_create_success(array(), $params, 'PostCode', 'Get');
}


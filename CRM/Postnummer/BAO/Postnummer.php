<?php
/**
 * BAO Postnummer for dealing with Norwegian post codes
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 *
 * Copyright (C) 2014 Coöperatieve CiviCooP U.A. <http://www.civicoop.org>
 * Licensed to MAF Norge <http://www.maf.no> and CiviCRM under the AGPL-3.0
 */
class CRM_Postnummer_BAO_Postnummer extends CRM_Postnummer_DAO_Postnummer {
  /**
   * Function to get values
   *
   * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
   * @return array $result found rows with data
   * @access public
   * @static
   */
  public static function getValues($params) {
    $result = array();
    $postCode = new CRM_Postnummer_BAO_Postnummer();
    if (!empty($params)) {
      $fields = self::fields();
      foreach ($params as $key => $value) {
        if (isset($fields[$key])) {
          $postCode->$key = $value;
        }
      }
    }
    $postCode->find();
    while ($postCode->fetch()) {
      $row = array();
      self::storeValues($postCode, $row);
      $result[] = $row;
    }
    return $result;
  }
  /**
   * Function to add or update postnummer
   *
   * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
   * @param array $params
   * @throws Exception when params empty
   * @return array $result
   * @access public
   * @static
   */
  public static function add($params) {
    $result = array();
    if (empty($params)) {
      $config = CRM_Postnummer_Config::singleton();
      throw new Exception($config->translate('Params can not be empty when adding or updating a post code'));
    }
    $postCode = new CRM_Postnummer_BAO_Postnummer();
    $fields = self::fields();
    foreach ($params as $key => $value) {
      if (isset($fields[$key])) {
        $postCode->$key = $value;
      }
    }
    $postCode->save();
    self::storeValues($postCode, $result);
    return $result;
  }
  /**
   * Function to delete a postnummer by post code
   *
   * @param string $postCodeId
   * @throws Exception when postCodeId is empty
   */
  public static function deleteByCode($postCodeId) {
    if (empty($postCodeId)) {
      $config = CRM_Postnummer_Config::singleton();
      throw new Exception($config->translate('postCodeId can not be empty when attempting to delete a postnummer'));
    }
    $postCode = new CRM_Postnummer_BAO_Postnummer();
    $postCode->post_code = $postCodeId;
    $postCode->delete();
  }
}
<?php
/**
 * DAO Postnummer for Norwegian post code table
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 *
 * Copyright (C) 2014 Coöperatieve CiviCooP U.A. <http://www.civicoop.org>
 * Licensed to MAF Norge <http://www.maf.no> and CiviCRM under the AGPL-3.0
 */
class CRM_Postnummer_DAO_Postnummer extends CRM_Core_DAO {
  /**
   * static instance to hold the field values
   *
   * @var array
   * @static
   */
  static $_fields = null;
  static $_export = null;
  /**
   * empty definition for virtual function
   */
  static function getTableName() {
    return 'civicrm_post_nummer';
  }
  /**
   * returns all the column names of this table
   *
   * @access public
   * @return array
   */
  static function &fields() {
    if (!(self::$_fields)) {
      self::$_fields = array(
          'post_code' => array(
              'name' => 'post_code',
              'type' => CRM_Utils_Type::T_STRING,
              'maxlength' => 15,
              'required' => true
          ) ,
          'post_city' => array(
              'name' => 'post_city',
              'type' => CRM_Utils_Type::T_STRING,
              'maxlength' => 128,
          ) ,
          'community_number' => array(
              'name' => 'community_number',
              'type' => CRM_Utils_Type::T_STRING,
              'maxlength' => 15,
          ),
          'community_name' => array(
              'name' => 'community_name',
              'type' => CRM_Utils_Type::T_STRING,
              'maxlength' => 128,
          ),
          'category' => array(
              'name' => 'category',
              'type' => CRM_Utils_Type::T_STRING,
              'maxlength' => 1,
          ),
      );
    }
    return self::$_fields;
  }
  /**
   * Returns an array containing, for each field, the array key used for that
   * field in self::$_fields.
   *
   * @access public
   * @return array
   */
  static function &fieldKeys() {
    if (!(self::$_fieldKeys)) {
      self::$_fieldKeys = array(
          'post_code' => 'post_code',
          'post_city' => 'post_city',
          'community_number' => 'community_number',
          'community_name' => 'community_name',
          'category' => 'category'
      );
    }
    return self::$_fieldKeys;
  }
  /**
   * returns the list of fields that can be exported
   *
   * @access public
   * return array
   * @static
   */
  static function &export($prefix = false)
  {
    if (!(self::$_export)) {
      self::$_export = array();
      $fields = self::fields();
      foreach($fields as $name => $field) {
        if (CRM_Utils_Array::value('export', $field)) {
          if ($prefix) {
            self::$_export['activity'] = & $fields[$name];
          } else {
            self::$_export[$name] = & $fields[$name];
          }
        }
      }
    }
    return self::$_export;
  }
}
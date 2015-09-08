<?php
/**
 * Page Postnummer to list all post codes
 *
 * @author Erik Hommel <erik.hommel@civicoop.org>
 *
 * Copyright (C) 2014 Coöperatieve CiviCooP U.A. <http://www.civicoop.org>
 * Licensed to MAF Norge <http://www.maf.no> under the  AGPL-3.0
 */
require_once 'CRM/Core/Page.php';

// TODO : replace page with CustomSearch????

class CRM_Postnummer_Page_Postnummer extends CRM_Core_Page {

  /**
   * Standard run method created when generating page with Civix
   *
   * @access public
   */
  function run() {
    $this->setPageConfiguration();
    $displayPostCodes = $this->getPostCodes();
    $this->assign('postCodes', $displayPostCodes);
    parent::run();
  }
  /**
   * Method to get the data from civicrm_post_nummer
   *
   * @return array $postCodes
   * @access protected
   */
  protected function getPostCodes() {
    $postCodes = CRM_Postnummer_BAO_Postnummer::getValues(array());
    foreach ($postCodes as $rowNum => $postCode) {
      $postCodes[$rowNum]['actions'] = $this->setRowActions($postCode);
    }
    return $postCodes;
  }

  /**
   * Method to set the row action urls and links for each row
   *
   * @param int $postCode
   * @return array $pageActions
   * @access protected
   */
  protected function setRowActions($postCode) {
    $pageActions = array();
    $editUrl = CRM_Utils_System::url('civicrm/postnummer', 'action=update&reset=1&pc='.$postCode['post_code']);
    $pageActions[] = '<a class="action-item" title="Edit" href="'.$editUrl.'">Edit</a>';
    $deleteUrl = CRM_Utils_System::url('civicrm/postnummer', 'action=delete&reset=1&pc='.$postCode['post_code']);
    $pageActions[] = '<a class="action-item" title="Delete" href="'.$deleteUrl.'">Delete</a>';
    return $pageActions;
  }

  /**
   * Method to set the page configuration
   *
   * @access protected
   */
  protected function setPageConfiguration() {
    $config = CRM_Postnummer_Config::singleton();
    CRM_Utils_System::setTitle(ts('Postnummer'));
    $this->assign('addUrl', CRM_Utils_System::url('civicrm/postnmmer','action=add&reset=1', true));
    $this->assign('newButtonLabel', $config->translate('New Postnummer'));
    $session = CRM_Core_Session::singleton();
    $session->pushUserContext(CRM_Utils_System::url('civicrm/postnummerlist', 'reset=1', true));
  }
}

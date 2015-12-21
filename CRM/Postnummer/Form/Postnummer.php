<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Postnummer_Form_Postnummer extends CRM_Core_Form {

  private $postCode = null;

  function buildQuickForm() {

    $this->postCode = CRM_Utils_Request::retrieve('pc', 'String');
    $config = CRM_Postnummer_Config::singleton();
    CRM_Utils_System::setTitle($config->translate("Post Code"));
    $this->assign('formHeader', $config->translate("Edit Post Code")." ".$this->postCode);

    // add form elements
    $this->add('hidden', 'post_code');
    $this->add('text', 'post_city', $config->translate('Post City'), array(), true);
    $this->add('text', 'community_number', $config->translate('Community Number'), array(), true);
    $this->add('text', 'community_name', $config->translate('Community Name'), array(), true);
    $this->add('text', 'category', $config->translate('Category'), array('maxlength' => 1, 'size' => 1), true);
    $this->addButtons(array(
        array('type' => 'next', 'name' => ts('Save'), 'isDefault' => true,)));
    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  function setDefaultValues() {
    if (!empty($this->postCode)) {
      $defaults['post_code'] = $this->postCode;
      $query = "SELECT * FROM civicrm_post_nummer WHERE post_code = %1";
      $params = array(1 => array($this->postCode, "String"));
      $dao = CRM_Core_DAO::executeQuery($query, $params);
      if ($dao->fetch()) {
        $defaults['post_city'] = $dao->post_city;
        $defaults['community_number'] = $dao->community_number;
        $defaults['community_name'] = $dao->community_name;
        $defaults['category'] = $dao->category;
      }
      return $defaults;
    }
  }

  function postProcess() {
    $query = "UPDATE civicrm_post_nummer SET post_city = %1, community_number = %2, community_name = %3,
      category = %4 WHERE post_code = %5";
    $params = array(
      1 => array($this->_submitValues['post_city'], "String"),
      2 => array($this->_submitValues['community_number'], "String"),
      3 => array($this->_submitValues['community_name'], "String"),
      4 => array($this->_submitValues['category'], "String"),
      5 => array($this->_submitValues['post_code'], "String"));
    CRM_Core_DAO::executeQuery($query, $params);
    $session = CRM_Core_Session::singleton();
    $session->setStatus("Post Code ".$this->_submitValues['post_code']." saved", "Saved", "success");
    CRM_Utils_System::redirect($session->readUserContext());
    parent::postProcess();
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }
}

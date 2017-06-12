<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Postnummer_Form_Postnummer extends CRM_Core_Form {

  private $postCode = null;

  public function preProcess() {
    $this->postCode = CRM_Utils_Request::retrieve('pc', 'String');
  }

  function buildQuickForm() {
    $config = CRM_Postnummer_Config::singleton();
    CRM_Utils_System::setTitle("Post Code");
    $this->assign('formHeader', "Edit Post Code"." ".$this->postCode);

    // add form elements
    $this->assign('action', $this->_action);

    if ($this->_action == CRM_Core_Action::DELETE) {
      $this->add('hidden', 'post_code');
      $this->addButtons(array(
        array('type' => 'next', 'name' => ts('Delete'), 'isDefault' => true,),
        array('type' => 'cancel', 'name' => ts('Cancel'), 'isDefault' => true,)
      ));
    } else {
      $this->addButtons(array(array('type' => 'next', 'name' => ts('Save'), 'isDefault' => true,)));

      if ($this->_action == CRM_Core_Action::ADD) {
        $this->add('text', 'post_code', 'Post Code', array(), true);
        $this->addFormRule(array('CRM_Postnummer_Form_Postnummer', 'postCodeDoesNotExists'));
      } else {
        $this->add('hidden', 'post_code');
      }

      $this->add('text', 'post_city', 'Post City', array(), true);
      $this->add('text', 'community_number', 'Community Number', array(), true);
      $this->add('text', 'community_name', 'Community Name', array(), true);
      $this->add('text', 'category', 'Category', array('maxlength' => 1, 'size' => 1), true);
    }

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());

    parent::buildQuickForm();
  }

  public static function postCodeDoesNotExists($fields) {
    $errors = array();
    $exists = CRM_Core_DAO::singleValueQuery("SELECT COUNT(*) FROM civicrm_post_nummer WHERE post_code = %1", array(1=>array($fields['post_code'], 'String')));
    if ($exists > 0) {
      $errors['post_code'] = ts('Post code exists');
    }
    return $errors;
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
    if ($this->_action == CRM_Core_Action::DELETE) {
      $query = "DELETE FROM civicrm_post_nummer WHERE post_code = %1";
      $params = array(
        1 => array($this->_submitValues['post_code'], "String")
      );
      CRM_Core_DAO::executeQuery($query, $params);
      $session = CRM_Core_Session::singleton();
      $session->setStatus("Post Code " . $this->_submitValues['post_code'] . " deleted", "Deleted", "success");
    } elseif ($this->_action == CRM_Core_Action::ADD) {
      $query = "INSERT INTO civicrm_post_nummer (post_code, post_city, community_number, community_name, category) 
      VALUES (%1, %2, %3, %4, %5);";
      $params = array(
        1 => array($this->_submitValues['post_code'], "String"),
        2 => array($this->_submitValues['post_city'], "String"),
        3 => array($this->_submitValues['community_number'], "String"),
        4 => array($this->_submitValues['community_name'], "String"),
        5 => array($this->_submitValues['category'], "String"),
      );
      CRM_Core_DAO::executeQuery($query, $params);
      $session = CRM_Core_Session::singleton();
      $session->setStatus("Post Code " . $this->_submitValues['post_code'] . " saved", "Saved", "success");
    } else {
      $query = "UPDATE civicrm_post_nummer SET post_city = %1, community_number = %2, community_name = %3,
        category = %4 WHERE post_code = %5";
      $params = array(
        1 => array($this->_submitValues['post_city'], "String"),
        2 => array($this->_submitValues['community_number'], "String"),
        3 => array($this->_submitValues['community_name'], "String"),
        4 => array($this->_submitValues['category'], "String"),
        5 => array($this->_submitValues['post_code'], "String")
      );
      CRM_Core_DAO::executeQuery($query, $params);
      $session = CRM_Core_Session::singleton();
      $session->setStatus("Post Code " . $this->_submitValues['post_code'] . " saved", "Saved", "success");
    }
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

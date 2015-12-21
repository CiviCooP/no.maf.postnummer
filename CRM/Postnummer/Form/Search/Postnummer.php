<?php

/**
 * A custom contact search
 */
class CRM_Postnummer_Form_Search_Postnummer extends CRM_Contact_Form_Search_Custom_Base implements CRM_Contact_Form_Search_Interface {

  private $citiesList = array();

  function __construct(&$formValues) {
    parent::__construct($formValues);
    $this->getDistinctCities();
  }

  /**
   * Prepare a set of search fields
   *
   * @param CRM_Core_Form $form modifiable
   * @return void
   */
  function buildForm(&$form) {
    CRM_Utils_System::setTitle(ts('Find Post Code'));

    $form->add('text', 'post_code', 'Post Code');
    $form->add('select', 'post_city', 'Post City', $this->citiesList);

    $defaults = $this->retrieveDefaultValues();
    if (!empty($defaults)) {
      $form->setDefaults($defaults);
    }
    $form->assign('elements', array('post_code', 'post_city'));
  }

  /**
   * Get a list of displayable columns
   *
   * @return array, keys are printable column headers and values are SQL column names
   */
  function &columns() {
    // return by reference
    $columns = array(
      'Post Code' => 'post_code',
      'City' => 'post_city',
      'Community Number' => 'community_number',
      'Community Name' => 'community_name',
      'Category' => 'category',
    );
    return $columns;
  }
  /**
   * Construct a full SQL query which returns one page worth of results
   *
   * @param int $offset
   * @param int $rowcount
   * @param null $sort
   * @param bool $includeContactIDs
   * @param bool $justIDs
   * @return string, sql
   */
  function all($offset = 0, $rowcount = 0, $sort = NULL, $includeContactIDs = FALSE, $justIDs = FALSE) {
    $session = CRM_Core_Session::singleton();
    $userContext = $this->_formValues['entryURL'];
    if (!empty($this->_formValues['post_city'])) {
      $userContext .= "&dc=".$this->_formValues['post_city'];
    }
    if (!empty($this->_formValues['post_code'])) {
      $userContext .= "&pc=".$this->_formValues['post_code'];
    }
    $session->pushUserContext($userContext);
    // delegate to $this->sql(), $this->select(), $this->from(), $this->where(), etc.
    return $this->sql($this->select(), $offset, $rowcount, $sort, FALSE, NULL);
  }

  /**
   * Method to count selected contacts
   *
   * @return string
   */
  function count() {
    $count = 0;
    $clause = array();
    $params = array();
    if (!empty($this->_formValues['post_code'])) {
      $count++;
      $clause[] = "post_code LIKE %".$count;
      $params[$count] = array($this->_formValues['post_code']."%", 'String');
    }

    if (!empty($this->_formValues['post_city'])) {
      $count++;
      $clause[] = "post_city = %".$count;
      $params[$count] = array($this->citiesList[$this->_formValues['post_city']], 'String');
    }
    $query = "SELECT COUNT(DISTINCT(post_code)) AS total FROM civicrm_post_nummer";
    if (!empty($clause)) {
      $query .= " WHERE ".implode(" AND ", $clause);
    }
    return CRM_Core_DAO::singleValueQuery($query, $params) ;
  }

  /**
   * Construct a SQL SELECT clause
   *
   * @return string, sql fragment with SELECT arguments
   */
  function select() {
    return "*";
  }

  /**
   * Construct a SQL FROM clause
   *
   * @return string, sql fragment with FROM and JOIN clauses
   */
  function from() {
    return "FROM civicrm_post_nummer";
  }

  /**
   * Construct a SQL WHERE clause
   *
   * @param bool $includeContactIDs
   * @return string, sql fragment with conditional expressions
   */
  function where($includeContactIDs = FALSE) {
    $params = array();
    $clause = array();
    $count = 0;

    if (!empty($this->_formValues['post_code'])) {
      $count++;
      $clause[] = "post_code LIKE %".$count;
      $params[$count] = array($this->_formValues['post_code']."%", 'String');
    }

    if (!empty($this->_formValues['post_city'])) {
      $count++;
      $clause[] = "post_city = %".$count;
      $params[$count] = array($this->citiesList[$this->_formValues['post_city']], 'String');
    }

    if (!empty($clause)) {
      $where = implode(' AND ', $clause);
    }
    return $this->whereClause($where, $params);
  }

  /**
   * Determine the Smarty template for the search screen
   *
   * @return string, template path (findable through Smarty template path)
   */
  function templateFile() {
    return 'CRM/Postnummer/Page/SearchResult.tpl';
  }

  /**
   * Method to get distinct cities
   *
   * @return array
   * @access private
   */
  private function getDistinctCities() {
    $this->citiesList[0] = "- all -";
    $query = "SELECT DISTINCT(post_city) FROM civicrm_post_nummer ORDER BY post_city";
    $dao = CRM_Core_DAO::executeQuery($query);
    while ($dao->fetch()) {
      $this->citiesList[] = $dao->post_city;
    }
  }

  /**
   * Empty function to make sure parent does not disallow query
   *
   * @param $sql
   * @param bool|FALSE $onlyWhere
   */
  function validateUserSQL(&$sql, $onlyWhere = FALSE) {
  }

  function retrieveDefaultValues() {
    $defaults = array();
    $defaultCode = CRM_Utils_Request::retrieve("pc", "String");
    $defaultCity = CRM_Utils_Request::retrieve("dc", "String");
    if (!empty($defaultCity)) {
      $defaults['post_city'] = $defaultCity;
    }
    if (!empty($defaultCode)) {
      $defaults['post_code'] = $defaultCode;
    }
    return $defaults;
  }
}

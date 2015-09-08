<?php

/**
 * A custom contact search
 */
class CRM_Postnummer_Form_Search_Postnummer extends CRM_Contact_Form_Search_Custom_Base implements CRM_Contact_Form_Search_Interface {

  private $citiesList = array();
  private $postCodeList = array();

  function __construct(&$formValues) {
    parent::__construct($formValues);
    $this->getDistinctCities();
    $this->getDistinctPostCodes();
  }

  /**
   * Prepare a set of search fields
   *
   * @param CRM_Core_Form $form modifiable
   * @return void
   */
  function buildForm(&$form) {
    CRM_Utils_System::setTitle(ts('Find Post Code'));
    $config = CRM_Postnummer_Config::singleton();

    $form->add('select', 'post_code', $config->translate('Post Code'), $this->postCodeList);
    $form->add('select', 'post_city', $config->translate('Post City'), $this->citiesList);

    // Optionally define default search values
    $form->setDefaults(array(
      'post_code' => '',
      'post_city' => NULL,
    ));

    /**
     * if you are using the standard template, this array tells the template what elements
     * are part of the search criteria
     */
    $form->assign('elements', array('post_code', 'post_city'));
  }

  /**
   * Get a list of summary data points
   *
   * @return mixed; NULL or array with keys:
   *  - summary: string
   *  - total: numeric
   */
  function summary() {
    return NULL;
  }

  /**
   * Get a list of displayable columns
   *
   * @return array, keys are printable column headers and values are SQL column names
   */
  function &columns() {
    // return by reference
    $config = CRM_Postnummer_Config::singleton();
    $columns = array(
      $config->translate('Post Code') => 'post_code',
      $config->translate('City') => 'post_city',
      $config->translate('Community Number') => 'community_number',
      $config->translate('Community Name') => 'community_name',
      $config->translate('Category') => 'category',
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

    // delegate to $this->sql(), $this->select(), $this->from(), $this->where(), etc.
    return $this->sql($this->select(), $offset, $rowcount, $sort, FALSE, NULL);
  }

  /**
   * Method to count selected contacts
   *
   * @return string
   */
  function count() {
    $query = "SELECT COUNT(DISTINCT(post_code)) AS total FROM civicrm_post_nummer";
    return CRM_Core_DAO::singleValueQuery($query) ;
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
    return "";
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
   * Modify the content of each row
   *
   * @param array $row modifiable SQL result row
   * @throws exception if function getOptionGroup not found
   * @return void
   */
  function alterRow(&$row) {
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
   * Method to get distinct post codes
   *
   * @return array
   * @access private
   */
  private function getDistinctPostCodes() {
    $this->postCodeList[0] = "- all -";
    $query = "SELECT DISTINCT(post_code) FROM civicrm_post_nummer ORDER BY post_code";
    $dao = CRM_Core_DAO::executeQuery($query);
    while ($dao->fetch()) {
      $this->postCodeList[] = $dao->post_code;
    }
  }
  function validateUserSQL(&$sql, $onlyWhere = FALSE) {
  }
}

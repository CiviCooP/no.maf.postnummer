<?php
/**
 * Abstract import data source
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license AGPL-3.0
 */
abstract class CRM_Postnummer_DataSource {

  /**
   * this array holds an array,
   *  mapping the source's attributes to the internally understood ones
   */
  protected $mapping = NULL;

  /**
   * the URI identifies each data source
   */
  protected $uri = NULL;

  /**
   * Constructor method
   * @param string $uri
   * @param array $mapping
   * @throws CiviCRM_API3_Exception
   * @access public
   */
  public function __construct($uri, $mapping = NULL) {
    $this->uri = $uri;
    if ($mapping == NULL) {
      $config = CRM_Postnummer_Config::singleton();
      $mapping = $config->getDefaultMapping();
    }
    $this->mapping = $mapping;
  }

  /**
   * Will reset the status of the data source
   */
  public abstract function reset();

  /**
   * Check if there is (more) records available
   *
   * @return true if there is more records available via next()
   */
  public abstract function hasNext();

  /**
   * Get the next record
   *
   * @return array containing the record
   */
  public abstract function next();

  /**
   * transforms the given record's keys according to $this->mapping
   * missing keys in the mapping will be treated according to the $restrict parameter
   * mappings to null or '' will be removed in any case
   *
   * @param array $record    an array with the data
   * @param bool $restrict  if true, only the fields specified in the mapping will be copied
   * @return array with the transformed keys
   * @access protected
   */
  protected function applyMapping($record, $restrict=false) {
    $newRecord = array();
    foreach ($record as $key => $value) {
      if (isset($this->mapping[$key])) {
        $newKey = $this->mapping[$key];
      } else {
        if ($restrict) {
          continue;
        } else {
          $newKey = $key;
        }
      }
      $newRecord[$newKey] = $value;
    }
    return $newRecord;
  }
}
<?php
/**
 * This importer will take a local csv file parse individual records
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license AGPL-3.0
 */
class CRM_Postnummer_FileCsvDataSource extends CRM_Postnummer_DataSource {

  protected $default_delimiter = ';';
  protected $default_encoding  = 'UTF8';
  
  /** this will hold the open file */
  protected $reader  = NULL;

  /** this will hold the header of the open file */
  protected $header  = NULL;

  /** this will hold the record to be delivered next */
  protected $next    = NULL;

  /** this will hold the line number */
  protected $line_nr = 0;

  /** this will hold the logging messages */
  public $logger = array();

  /**
   * Will reset the status of the data source
   *
   * @access public
   */
  public function reset() {
    $config = CRM_Postnummer_Config::singleton();
    $this->setSeparator();
    // try loading the given file
    $this->reader  = fopen($this->uri, 'r');
    $this->header  = NULL;
    $this->next    = NULL;
    $this->line_nr = 0;

    if (empty($this->reader)) {
      $this->logger[] = "Unable to read file ".$this->uri;
      $this->reader = NULL;
      return;
    }

    // read header
    $this->header = fgetcsv($this->reader, 0, $this->default_delimiter);
    if ($this->header == NULL) {
      $this->logger[] = $this->uri." does not contain headers";
      $this->reader = NULL;
      return;
    }

    // prepare the next record
    $this->loadNext();
  }

  /**
   * Check if there is (more) records available
   *
   * @return true if there is more records available via next()
   * @access public
   */
  public function hasNext() {
    return ($this->next != NULL);
  }

  /**
   * Get the next record
   *
   * @return array containing the record
   * @access public
   */
  public function next() {
    if ($this->hasNext()) {
      $record = $this->next;
      $this->loadNext();
      return $record;
    } else {
      return NULL;
    }
  }

  /**
   * will load the next data record from the file
   *
   * @access protected
   */
  protected function loadNext() {
    if ($this->reader == NULL) {
      // either not initialised or complete...
      return NULL;
    }

    // read next data blob
    $this->next = NULL;
    $this->line_nr += 1;
    $data = fgetcsv($this->reader, 0, $this->default_delimiter);
    if ($data == NULL) {
      // there is no more records => reset
      fclose($this->reader);
      $this->reader = NULL;
    } else {
      // data blob read, build record
      $record = array();
      foreach ($this->header as $index => $key) {
        if (isset($data[$index])) {
          $record[$key] = $data[$index];
        }
      }
      $this->next = $this->applyMapping($record);

      // set ID if not defined by file/mapping
      if (empty($this->next['__id'])) $this->next['__id'] = $this->line_nr;      
    }
  }
  /**
   * Function to check which csv separator to use. Assumption is that
   * separator is ';', if reading first record return record with only
   * 1 field, then ',' should be used
   *
   * @access public
   */
  public function setSeparator() {
    $testSeparator = fopen($this->uri, 'r');
    /*
     * first test if semi-colon or comma separated, based on assumption that
     * it is semi-colon and it should be comma if I only get one record then
     */
    if ($testRow = fgetcsv($testSeparator, 0, ';')) {
      if (!isset($testRow[1])) {
        $this->default_delimiter = ",";
      } else {
        $this->default_delimiter = ";";
      }
    }
    fclose($testSeparator);
  }
}
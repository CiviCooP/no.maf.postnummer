<?php
/**
 * Class to handle the individual records
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license AGPL-3.0
 */
class CRM_Postnummer_RecordHandler {

  /**
   * stores the result/logging object
   */ 
  protected $logger = NULL;

  public function __construct() {
    $this->logger = array();
  }

  /** 
   * Check if the given handler implementation can process the record
   *
   * @param $record  an array of key=>value pairs
   * @return true or false
   */
  public function canProcessRecord($record) {

  }

  /** 
   * process the given record
   *
   * @param array $record an array of key=>value pairs
   * @return true
   * @throws exception if failed
   */
  public function processRecord($record) {
    $config = CRM_Postnummer_Config::singleton();
    if ($this->postNummerExists($record['PO Box Number']) == TRUE) {
      $query = 'UPDATE civicrm_post_nummer SET post_sted = %1, kommune_nummer = %2, kommune_navn = %3, kategori = %3
        WHERE post_nummer = %4';
    } else {
      $query = 'INSERT INTO civicrm_post_nummer SET post_sted = %1, kommune_nummer = %2, kommune_navn = %3,
        kategori = %3, post_nummer = %4';
    }
    $params = array(
      1 => array($record['City'], 'String'),
      2 => array($record['Quarter Number'], 'String'),
      3 => array($record['Quarter Name'], 'String'),
      4 => array($record['PO Box Number'], 'String')
    );
    try {
      CRM_Core_DAO::executeQuery($query, $params);
    } catch (Exception $ex) {
      throw new Exception($config->translate("Unable to create or update civicrm_post_nummer with data")." ".
        implode("; ", $record).", ".$config->translate("error from database").": ".$ex->getMessage());
    }
    return TRUE;
  }
  /**
   * Method to check if postnummer already exists in database
   *
   * @param string $postNummer
   * @return bool
   * @access private
   */
  private function postNummerExists($postNummer) {
    // TODO: implement
  }

  /** 
   * process all records of the given data source
   *
   * @param $dataSource  a CRM_Streetimport_DataSource object
   */
  public static function processDataSource($dataSource) {
    $dataSource->reset();
    $handler = new CRM_Postnummer_RecordHandler();
    $counter = 0;
    while ($dataSource->hasNext()) {
      $record = $dataSource->next();
      $counter += 1;
      $record_processed = FALSE;
      if ($handler->canProcessRecord($record)) {
          $handler->processRecord($record);
          $record_processed = TRUE;
      }
      if (!$record_processed) {
        $config = CRM_Streetimport_Config::singleton();
        // no handlers found.
        $handler->logger[] = $config->translate('Could not process record from csv file line number').' '.$dataSource->line_nr;
      }
    }
  }
}
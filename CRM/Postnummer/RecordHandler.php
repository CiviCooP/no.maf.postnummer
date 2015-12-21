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
   * process the given record
   *
   * @param array $record an array of key=>value pairs
   * @throws exception if failed
   */
  public function processRecord($record) {
    $params = array(
      'post_city' => $record['City'],
      'community_number' => $record['Community Number'],
      'community_name' => $record['Community Name'],
      'category' => $record['Category'],
      'post_code' => $record['Post Code']
    );
    civicrm_api3('Postnummer', 'Create', $params);
  }

  /** 
   * process all records of the given data source
   *
   * @param object $dataSource CRM_Postnummer_DataSource object
   */
  public static function processDataSource($dataSource) {
    $dataSource->reset();
    $handler = new CRM_Postnummer_RecordHandler();
    $counter = 0;
    while ($dataSource->hasNext()) {
      $record = $dataSource->next();
      $counter += 1;
      $handler->processRecord($record);
    }
  }
}
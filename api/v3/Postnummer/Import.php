<?php
/**
 * Basic API for postnummer importer
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license AGPL-3.0
 */

/**
 * import a CSV file, either by name, or from a given path
 *
 * @param $params['source_folder']  local file path where to look for the files
*                                   if not set, defaults to CRM_Postnummer_Config->getImportFileLocation()
 * @return array API result array
 * @access public
 */
function civicrm_api3_postnummer_import($params) {
  $logMessages = array();
  $config = CRM_Postnummer_Config::singleton();

  if (isset($params['source_folder'])) {
    $source_folder = $params['source_folder'];
  } else {
    $source_folder = $config->getImportFileLocation();
  }

  $source_file = NULL;
  $files = glob($source_folder . "/*.csv");
  sort($files);

  // now run the actual import
  foreach ($files as $source_file) {
    try {
      if (!$source_file) {
        $logMessages[] = $config->translate("No source files found");
      } else {
        $dataSource = new CRM_Postnummer_FileCsvDataSource($source_file);
        CRM_Postnummer_RecordHandler::processDataSource($dataSource);
        $logMessages = $logMessages + $dataSource->logger;
        $logMessages[] = $config->translate("Imported file")." ".$source_file." ".$config->translate("successfully");
      }
    } catch (Exception $ex) {
      // whole import was aborted...
      $logMessages[] = $config->translate("Aborted import of file")." ".$source_file.", ".
        $config->translate("Exception was").": ".$ex->getMessage();
      }
    }
  return setResult($logMessages);
}
function setResult($logMessages) {
  //TODO implement
}


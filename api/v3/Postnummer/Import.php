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
  CRM_Core_DAO::executeQuery('TRUNCATE TABLE civicrm_post_nummer');
  $config = CRM_Postnummer_Config::singleton();

  if (isset($params['source_folder'])) {
    $sourceFolder = $params['source_folder'];
  } else {
    $sourceFolder = $config->getImportFileLocation();
  }


  $sourceFile = NULL;
  $files = glob($sourceFolder . "/*.csv");
  sort($files);

  // now run the actual import
  foreach ($files as $sourceFile) {
    try {
      if ($sourceFile) {
        $dataSource = new CRM_Postnummer_FileCsvDataSource($sourceFile);
        CRM_Postnummer_RecordHandler::processDataSource($dataSource);
      }
    } catch (Exception $ex) {
      throw new API_Exception('Could not import postnummer file, error : '.$ex->getMessage());
    }
  }
  return civicrm_api3_create_success(array(), $params, 'Postnummer', 'Import');
}


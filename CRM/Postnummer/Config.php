<?php
/**
 * Class following Singleton pattern for specific extension configuration
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @date 12 Aug 2015
 * @license AGPL-3.0
 */
class CRM_Postnummer_Config {

  private static $_singleton;

  protected $resourcesPath = null;
  protected $importPath = null;
  protected $importSettings = array();
  protected $translatedStrings = array();
  protected $defaultMapping = array();

  /**
   * Constructor method
   *
   * @param string $context
   */
  function __construct($context) {
    $settings = civicrm_api3('Setting', 'Getsingle', array());
    $this->resourcesPath = $settings['extensionsDir'].'/no.maf.postnummer/resources/';
    $this->setImportSettings();
    $this->importPath = $settings['customFileUploadDir'].$this->importSettings['import_location']['value'];
    $this->setDefaultMapping();
  }

  /**
   * Method to retrieve import settings
   *
   * @return array
   * @access public
   */
  public function getImportSettings() {
    return $this->importSettings;
  }

  /**
   * Method to retrieve default mapping
   *
   * @return array
   * @access public
   */
  public function getDefaultMapping() {
    return $this->defaultMapping;
  }

  /**
   * Method to retrieve import file location
   *
   * @return string
   * @access public
   */
  public function getImportFileLocation() {
    return $this->importPath;
  }

  /**
   * Singleton method
   *
   * @param string $context to determine if triggered from install hook
   * @return CRM_Postnummer_Config
   * @access public
   * @static
   */
  public static function singleton($context = null) {
    if (!self::$_singleton) {
      self::$_singleton = new CRM_Postnummer_Config($context);
    }
    return self::$_singleton;
  }

  /**
   * Method to save the import settings
   *
   * @param array $params
   * @throws Exception when json file could not be opened
   * @access public
   */
  public function saveImportSettings($params) {
    if (!empty($params)) {
      foreach ($params as $key => $value) {
        if (isset($this->importSettings[$key])) {
          $this->importSettings[$key]['value'] = $value;
        }
      }
      $fileName = $this->resourcesPath . 'import_settings.json';
      try {
        $fh = fopen($fileName, 'w');
        fwrite($fh, json_encode($this->importSettings, JSON_PRETTY_PRINT));
        fclose($fh);
      } catch (Exception $ex) {
        throw new Exception('Could not open import_settings.json, contact your system administrator. Error reported: ' . $ex->getMessage());
      }
    }
  }

  /**
   * Method to set the Import Settings property
   *
   * @throws Exception when file not found
   * @access protected
   */
  protected function setImportSettings() {
    $jsonFile = $this->resourcesPath.'import_settings.json';
    if (!file_exists($jsonFile)) {
      throw new Exception('Could not load import_settings configuration file for extension, contact your system administrator!');
    }
    $importSettingsJson = file_get_contents($jsonFile);
    $this->importSettings = json_decode($importSettingsJson, true);
  }

  /**
   * Method to set default mapping
   *
   * @throws Exception if mapping could not be loaded
   * @access protected
   */
  protected function setDefaultMapping() {
    $jsonFile = $this->resourcesPath.'default_mapping.json';
    if (file_exists($jsonFile)) {
      $mappingJson = file_get_contents($jsonFile);
      $this->defaultMapping = json_decode($mappingJson, true);
    } else {
      throw new Exception('Could not load default mappings for extension, contact your system administrator');
    }
  }
}
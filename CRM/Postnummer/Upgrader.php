<?php

/**
 * Collection of upgrade steps.
 */
class CRM_Postnummer_Upgrader extends CRM_Postnummer_Upgrader_Base
{

  // By convention, functions that look like "function upgrade_NNNN()" are
  // upgrade tasks. They are executed in order (like Drupal's hook_update_N).

  /**
   * Create tables on install
   */
  public function install() {
    $this->executeSqlFile('sql/create_civicrm_post_nummer.sql');
  }
}
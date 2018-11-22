<?php

namespace Drupal\sp_migration\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;

/**
 * Trim text data in this case.
 *
 * @MigrateProcessPlugin(
 *   id = "normalize_auto_data"
 * )
 */
class NormalizeAutoDataProcessPlugin extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, \Drupal\migrate\MigrateExecutableInterface $migrate_executable, \Drupal\migrate\Row $row, $destination_property) {
    return trim($value);
  }

}
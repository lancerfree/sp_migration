<?php

namespace Drupal\sp_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\sp_migration\Common\AutoVocabularyXML;

/**
 * Read data from xml file.
 *
 * @MigrateSource(
 *   id = "auto_vocabulary_source"
 * )
 */
class XMLFileSourcePlugin extends SourcePluginBase {

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [];

    $fields['name'] = 'Auto name Source';
    $fields['description'] = 'Auto description Source';

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return get_class();
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'name' => [
        'type' => 'string',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function initializeIterator() {
    $module_handler = \Drupal::service('module_handler');
    $path = $module_handler->getModule('sp_migration')->getPath();
    $path_to_xml = $path . '/assets/' . $this->configuration['assets_file_path'];
    //Call external helper class
    $helper_xml = new AutoVocabularyXML($path_to_xml);
    $list_term = $helper_xml->getTermArray();

    return new \ArrayIterator($list_term);
  }

}
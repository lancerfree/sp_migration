<?php

namespace Drupal\sp_migration\Common;

/**
 * Main class for work with auto's xml
 */
class AutoVocabularyXML {

  /**
   * Contains data from xml.
   *
   * @var  array
   */
  protected $xmlData;

  /**
   * AutoVocabularyXML Constructor
   *
   * @param string
   *   Path to xml file.
   */
  public function __construct(string $path_to_file) {
    if (file_exists($path_to_file)) {
      $xml_file_content = simplexml_load_file($path_to_file, "SimpleXMLElement", LIBXML_NOCDATA);
      $interim_json_data = json_encode($xml_file_content);
      $this->xmlData = json_decode($interim_json_data, TRUE);
    }
  }

  /**
   * Extract partial data from full xml.
   *
   * @return array
   */
  public function getTermArray() {
    return $this->xmlData['terms']['term'];
  }

}
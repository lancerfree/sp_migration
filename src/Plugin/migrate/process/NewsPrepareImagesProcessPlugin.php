<?php

namespace Drupal\sp_migration\Plugin\migrate\process;

use Drupal\migrate\MigrateException;
use Drupal\migrate\ProcessPluginBase;
use Drupal\file\Entity\File;

/**
 * Moves images to destination folder.
 *
 * @MigrateProcessPlugin(
 *   id = "news_prepare_images_process_plugin",
 *   handle_multiples = TRUE
 * )
 */
class NewsPrepareImagesProcessPlugin extends ProcessPluginBase {

  /**
   * Destination path for use in all methods.
   *
   * @var string
   */
  public $destination_path;

  /**
   * CSV constructor.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, \Drupal\migrate\Plugin\MigrationInterface $migration) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $source_config = $migration->getSourceConfiguration();
    $this->destination_path = $source_config['constants']['image_destination_dir'];
    //Create directory if not exists
    $this->prepareImageDestinationDir($this->destination_path);
  }

  /**
   * Prepare destination image directory.
   *
   * @param string $path_image_dest
   *   Path to image destination folder.
   *
   * @throws \Drupal\migrate\MigrateException
   */
  public function prepareImageDestinationDir($path_image_dest) {
    if (!file_prepare_directory($path_image_dest, FILE_CREATE_DIRECTORY | FILE_MODIFY_PERMISSIONS)) {
      throw new MigrateException("It was not possible to create a directory \"$path_image_dest\". Check access to it.");
    }
  }


  /**
   * {@inheritdoc}
   */
  public function transform($value, \Drupal\migrate\MigrateExecutableInterface $migrate_executable, \Drupal\migrate\Row $row, $destination_property) {
    //if empty then skip process
    if (empty($value)) {
      return '';
    }

    $return_images = '';
    foreach ($value AS $value_id) {
      $image_entity = File::load($value_id['target_id']);
      if (!$image_entity) {
        continue;
      }
      $image_uri = $image_entity->getFileUri();
      $file_name = $image_entity->getFilename();
      //Normalize path - remove last slash
      $dest_path = rtrim($this->destination_path, '/\\');
      $returned_path = file_unmanaged_copy($image_uri, $dest_path . '//' . $file_name);
      if ($returned_path) {
        $return_images .= $this->extractFileName($returned_path) . ', ';
      }
    }
    $return_images = rtrim($return_images, ', ');

    return $return_images;
  }

  /**
   * Extract base file name from the path.
   *
   * @param string $file_path
   *   Uri to file.
   *
   * @return string
   */
  public function extractFileName($file_path) {
    $last_slash = strrpos($file_path, '/');
    return substr($file_path, $last_slash + 1);
  }

}
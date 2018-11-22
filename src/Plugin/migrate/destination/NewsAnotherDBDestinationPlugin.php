<?php

namespace Drupal\sp_migration\Plugin\migrate\destination;

use Drupal\migrate\Annotation\MigrateDestination;
use Drupal\migrate\MigrateException;
use Drupal\migrate\Plugin\migrate\destination\DestinationBase;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Row;
use Drupal\Core\Database\Database;


/**
 * Place data to the new db table.
 *
 * @MigrateDestination(
 *   id = "news_another_db_destination_plugin"
 * )
 */
class NewsAnotherDBDestinationPlugin extends DestinationBase {

  /**
   *   Rollback label.
   *
   * @var integer
   *  0|1
   */
  static public $rollback;

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'nid' => [
        'type' => 'integer',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function fields(MigrationInterface $migration = NULL) {

    $fields = [];
    $fields['nid'] = $this->t('News id.');
    $fields['status'] = $this->t('Status of publication.');
    $fields['title'] = $this->t('Title of the news.');
    $fields['created'] = $this->t('News creation time.');
    $fields['body'] = $this->t('Main content of the news.');
    $fields['images'] = $this->t('Images of the news. Joined string.');
    $fields['tags'] = $this->t('Tags of the news. Joined string.');

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function import(Row $row, array $old_destination_id_values = []) {
    // Connection with destination directory
    Database::setActiveConnection('destination_db');
    $db = Database::getConnection();
    $query = $db->insert('destination_news');
    $query->fields([
      'status' => $row->getDestinationProperty('status'),
      'title' => $row->getDestinationProperty('title'),
      'created' => $row->getDestinationProperty('created'),
      'body' => $row->getDestinationProperty('body'),
      'images' => $row->getDestinationProperty('images'),
      'tags' => $row->getDestinationProperty('tags'),
    ]);
    $last_id = $query->execute();
    // Set default connection
    Database::setActiveConnection();

    return [$last_id];
  }

  /**
   * {@inheritdoc}
   */
  public function supportsRollback() {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function rollback(array $destination_identifier) {
    Database::setActiveConnection('destination_db');

    $db = Database::getConnection();
    foreach ($destination_identifier AS $di_value) {
      $query = $db->delete('destination_news');
      $query->condition('nid', $di_value);
      $query->execute();
    }

    // Return default connection
    Database::setActiveConnection();
    //Clear once folder
    if (!isset(self::$rollback)) {
      self::$rollback = 1;
      $source_config = $this->migration->getSourceConfiguration();
      $this->emptyDir($source_config['constants']['image_destination_dir']);
    }
  }

  /**
   * Clear recursively directory.
   *
   * @param string
   *   Uri with a path to folder.
   */
  public function emptyDir($destination_dir) {
    file_unmanaged_delete_recursive($destination_dir);
  }

}
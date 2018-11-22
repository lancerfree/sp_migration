<?php

namespace Drupal\sp_migration\Plugin\migrate\process;

use Drupal\taxonomy\Entity\Term;
use Drupal\migrate\ProcessPluginBase;

/**
 * Contactenate tags names.
 *
 * @MigrateProcessPlugin(
 *   id = "news_prepare_tags_process_plugin",
 *   handle_multiples = TRUE
 * )
 */
class NewsPrepareTagsProcessPlugin extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, \Drupal\migrate\MigrateExecutableInterface $migrate_executable, \Drupal\migrate\Row $row, $destination_property) {
    $return_tags = '';
    if (!empty($value)) {
      foreach ($value AS $term_id) {
        $term_entity = Term::load($term_id['target_id']);
        if (!$term_entity) {
          continue;
        }
        /** @var  \Drupal\taxonomy\Entity\Term $term_entity */
        $return_tags .= $term_entity->getName() . ', ';
      }
      $return_tags = rtrim($return_tags, ', ');
    }

    return $return_tags;
  }

}
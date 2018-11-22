<?php

namespace Drupal\sp_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\sp_migration\Common\NodeNewsIterator;

/**
 * Prepares news for export.
 *
 * @MigrateSource(
 *   id = "news_source_plugin"
 * )
 */
class NewsSourcePlugin extends SourcePluginBase {

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [];
    $fields['nid'] = $this->t('News id.');
    $fields['status'] = $this->t('Status of publication.');
    $fields['title'] = $this->t('Title of the news.');
    $fields['created'] = $this->t('News creation time.');
    $fields['body'] = $this->t('Main content of the news.');
    $fields['images'] = $this->t('Images of the news.');
    $fields['tags'] = $this->t('Tags of the news.');

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
      'nid' => [
        'type' => 'integer',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function initializeIterator() {
    //Custom iterator. Becouse  unknown count news.
    return new NodeNewsIterator();
  }

}
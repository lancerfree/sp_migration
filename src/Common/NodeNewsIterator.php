<?php

namespace Drupal\sp_migration\Common;

/**
 * Helps iterate news for source plugin of migration.
 */
class NodeNewsIterator implements \Iterator {

  /**
   * Node bundle of news
   *
   * @var \Drupal\node\NodeInterface
   */
  public $node;

  /**
   * Id and key node
   *
   * @var integer
   */
  public $nodeID;


  /**
   * {@inheritdoc}
   */
  public function rewind() {
    unset($this->nodeID, $this->node);
    $this->next();
  }

  /**
   * {@inheritdoc}
   */
  public function current() {
    return $this->extractNews($this->node);
  }

  /**
   * {@inheritdoc}
   */
  public function key() {
    return $this->nodeID;
  }

  /**
   * {@inheritdoc}
   */
  public function next() {
    $this->nodeID = $this->nextID();
    $this->node = ($this->nodeID) ? $this->loadNews($this->nodeID) : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function valid() {
    return !empty($this->node);
  }

  /**
   * Select next id of the news type in db.
   *
   * If rewind was done then the first element would be returned.
   *
   * @return integer|FALSE
   *   Id next news.
   */
  public function nextID() {
    //last element not need do that operation
    if ($this->node === FALSE) {
      return FALSE;
    }

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'news');
    //previous element exist then select after it
    if (!empty($this->node)) {
      $current_node_time = $this->node->getCreatedTime();
      $query->condition('created', $current_node_time, '>');
    }
    $query->sort('created', 'ASC');
    $query->range(0, 1);
    $news_id = $query->execute();

    return ($news_id) ? reset($news_id) : FALSE;
  }

  /**
   * Load the whole entity by id.
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   *   Node of bundle news.
   * @throws
   */
  public function loadNews($id_node) {
    $node = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->load($id_node);

    return $node;
  }

  /**
   * Extract data from news.
   *
   * @param \Drupal\node\NodeInterface $node
   *   Node for extract.
   *
   * @return array
   * @throws
   */
  public function extractNews($node) {
    $ex_news['nid'] = $node->id();
    $ex_news['title'] = $node->getTitle();
    $ex_news['created'] = $node->getCreatedTime();
    $ex_news['status'] = $node->get('status')->first()->getValue()['value'];
    $ex_news['body'] = $node->get('body')->first()->getValue()['value'];
    $ex_news['images'] = $node->get('field_news_image')->getValue();
    $ex_news['tags'] = $node->get('field_news_tags_ref')->getValue();

    return $ex_news;
  }

}
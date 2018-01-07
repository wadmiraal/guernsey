<?php

namespace Drupal\guernsey\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Drupal\guernsey\BookInterface;

/**
 * Defines the Guernsey Book entity.
 * @ingroup guernsey
 *
 * @ContentEntityType(
 *   id = "guernsey_book",
 *   label = @Translation("Book entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\guernsey\Entity\Controller\BookListBuilder",
 *     "form" = {
 *       "add" = "Drupal\guernsey\Form\BookForm",
 *       "edit" = "Drupal\guernsey\Form\BookForm",
 *       "delete" = "Drupal\guernsey\Form\BookDeleteForm",
 *     },
 *   },
 *   list_cache_contexts = { "user" },
 *   base_table = "guernsey_books",
 *   admin_permission = "administer book entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/book/{guernsey_book}",
 *     "edit-form" = "/book/{guernsey_book}/edit",
 *     "delete-form" = "/book/{guernsey_book}/delete",
 *     "collection" = "/book/list"
 *   },
 * )
 */
class Book extends ContentEntityBase implements BookInterface {

  /**
   * {@inheritdoc}
   */
  public function __construct(array $values = array()) {
    return parent::__construct($values, 'guernsey_book');
  }

  /**
   * {@inheritdoc}
   */
  public function setChangedTime($timestamp) {
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getChangedTime() {
    return time();
  }

  /**
   * {@inheritdoc}
   */
  public function getChangedTimeAcrossTranslations()  {
    return time();
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return null;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return null;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = array();
    return $fields;
  }

}


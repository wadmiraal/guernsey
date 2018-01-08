<?php

namespace Drupal\guernsey\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Field\BaseFieldDefinition;
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
 *     "label" = "title",
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

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $values = array()) {
    return parent::__construct($values, 'guernsey_book');
  }

  /**
   * Sets the title of the book.
   *
   * @param string $title
   *   The title of the book.
   *
   * @return $this
   */
  public function setTitle($title) {
    $this->set('title', $title);
    return $this;
  }

  /**
   * Get the title of the book.
   *
   * @return string
   */
  public function getTitle() {
    return $this->get('title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = array();

    $fields['id'] = BaseFieldDefinition::create('id')
      ->setLabel('ID')
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel('UUID')
      ->setReadOnly(TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel('Title')
      ->setRequired(TRUE)
      ->setDescription('The title of the book.')
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -10,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -10,
      ])
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', FALSE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel('Created');

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel('Changed');

    $fields['author'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('Author(s)')
      ->setRequired(TRUE)
      ->setDescription(t('The author(s) of the book.'))
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler', 'default:taxonomy_term')
      ->setSetting('handler_settings', [
        'auto_create' => true,
        'target_bundles' => ['guernsey_authors' => 'guernsey_authors'],
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 3,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'autocomplete_type' => 'tags',
        ],
      ])
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', FALSE);

    return $fields;
  }

}


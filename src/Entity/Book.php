<?php

namespace Drupal\guernsey\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\user\UserInterface;

/**
 * Defines the Book entity.
 *
 * @ingroup guernsey
 *
 * @ContentEntityType(
 *   id = "guernsey_book",
 *   label = @Translation("Book"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\guernsey\BookListBuilder",
 *     "views_data" = "Drupal\guernsey\Entity\BookViewsData",
 *     "form" = {
 *       "default" = "Drupal\guernsey\Form\BookForm",
 *       "add" = "Drupal\guernsey\Form\BookForm",
 *       "edit" = "Drupal\guernsey\Form\BookForm",
 *       "delete" = "Drupal\guernsey\Form\BookDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\guernsey\BookHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "guernsey_book",
 *   admin_permission = "administer book entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "canonical" = "/book/{guernsey_book}",
 *     "add-form" = "/book/add",
 *     "edit-form" = "/book/{guernsey_book}/edit",
 *     "delete-form" = "/book/{guernsey_book}/delete",
 *     "collection" = "/book/list",
 *   },
 *   field_ui_base_route = "guernsey_book.settings"
 * )
 */
class Book extends ContentEntityBase implements BookInterface {

  const AUTHOR_VID = 'guernsey_authors';

  /**
   * Construct the Book entity.
   *
   * @param array $values
   *   (optional) An array of values to set, keyed by property name. Defaults to
   *   an empty array.
   */
  public function __construct(array $values = []) {
    parent::__construct($values, 'guernsey_book');
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->get('title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('title', $title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getAuthors() {
    $authors = array();
    foreach ($this->guernsey_book_authors as $value) {
      $authors[] = $value->entity->getName();
    }
    return $authors;
  }

  /**
   * {@inheritdoc}
   */
  public function setAuthors(array $authors) {
    foreach ($authors as $author) {
      $terms = taxonomy_term_load_multiple_by_name($author, self::AUTHOR_VID);
      if (!empty($terms)) {
        $term = reset($terms);
      }
      else {
        $term = Term::create([
          'name' => $author,
          'vid' => self::AUTHOR_VID,
          'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
        ]);
      }
      $this->guernsey_book_authors[] = ['entity' => $term];
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setRequired(TRUE)
      ->setDescription(t('The title of the book.'))
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
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['guernsey_book_authors'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('Author(s)')
      ->setRequired(TRUE)
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setDescription(t('The author(s) of the book.'))
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler', 'default:taxonomy_term')
      ->setSetting('handler_settings', [
        'auto_create' => true,
        'target_bundles' => [self::AUTHOR_VID => self::AUTHOR_VID],
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'autocomplete_type' => 'tags',
        ],
      ]);

    return $fields;
  }

}

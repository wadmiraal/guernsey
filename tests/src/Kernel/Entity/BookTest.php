<?php

namespace Drupal\guernsey\Tests\Kernel\Entity;

use Drupal\Core\Language\LanguageInterface;
use Drupal\KernelTests\KernelTestBase;
use Drupal\guernsey\Entity\Book;
use Drupal\taxonomy\Entity\Term;

class BookTest extends KernelTestBase {

  public static $modules = [
    'guernsey',
    'field',
    'text',
    'taxonomy',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->installEntitySchema('taxonomy_term');
    $this->installEntitySchema('guernsey_book');
    $this->installConfig(['field', 'guernsey']);
  }

  public function testGetSetTitle() {
    $book = new Book();
    $title = "Book title";
    $book->setTitle($title);
    $this->assertEquals($title, $book->getTitle(), "Getting/setting the title works.");
  }

  public function testGetSetAuthors() {
    $term = Term::create([
      'name' => "Jane Austen",
      'vid' => Book::AUTHOR_VID,
      'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
    ]);
    $term->save();
    $book = Book::create([
      'uuid' => $this->randomMachineName(),
      'title' => $this->randomMachineName(),
    ]);
    $authors = ["Harper Lee", $term->getName()];
    $book->setAuthors($authors);
    $this->assertEquals($authors, $book->getAuthors(), "Getting/setting the authors works.");

    // As long as the book isn't saved, it should not have created the new term.
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree(Book::AUTHOR_VID);
    $this->assertCount(1, $terms, "Only 1 author term exists.");

    // Save it, which should also trigger the creation of the 2nd author term.
    $book->save();
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree(Book::AUTHOR_VID);
    $this->assertCount(2, $terms, "The new author term was created.");

    // Check the created terms.
    foreach ($authors as $author) {
      $this->assertContains($author, array_map(function($term) {
        return $term->name;
      }, $terms));
    }
  }

}

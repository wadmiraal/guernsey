<?php

namespace Drupal\guernsey\Tests\Kernel\Entity;

use Drupal\KernelTests\KernelTestBase;
use Drupal\guernsey\Entity\Book;
use Drupal\taxonomy\Entity\Term;

class BookTest extends KernelTestBase {

  public static $modules = [
    'guernsey',
    'field',
    'taxonomy',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
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
      'vid' => 'guernsey_authors',
    ]);
    $term->save();
    $book = new Book();
    $authors = ["Harper Lee", $term->getName()];
    $book->setAuthors($authors);
    $this->assertEquals($authors, array_map(function($term) {
      return $term->name;
    }, $book->getAuthors()), "Getting/setting the authors works, and creates terms if they don't exist.");
    // TODO load terms, should have created 1.
  }

}

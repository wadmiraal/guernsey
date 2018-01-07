<?php

namespace Drupal\guernsey\Tests\Unit\Entity;

use Drupal\Core\Entity\EntityType;
use Drupal\Tests\UnitTestCase;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\guernsey\Entity\Book;

class BookTest extends UnitTestCase {

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    // Create a container for the DI. This must be done AFTER the parent setUp()
    // method has been called.
    $container = new ContainerBuilder();

    // Mock the entity manager, which is need to instantiate our entity.
    $entityManager = $this->getMock(EntityManagerInterface::class);
    $entityManager->expects($this->any())
      ->method('getDefinition')
      ->will($this->returnCallback(function() {
        $entityType = $this->getMock(EntityTypeInterface::class);
        $entityType->expects($this->any())
          ->method('getKey')
          ->will($this->returnValue(null));
        $entityType->expects($this->any())
          ->method('getKeys')
          ->will($this->returnValue([]));
        return $entityType;
      }));
    $entityManager->expects($this->any())
      ->method('getFieldDefinitions')
      ->will($this->returnCallback(function() {
        $entityType = $this->getMock(EntityTypeInterface::class);
        // We don't care about the fact that fields other than our base fields
        // can be added, so we simply return our base fields. Furthermore, we
        // don't need an entity type, but we still need to pass one to the
        // method. So we pass a mock.
        return Book::baseFieldDefinitions($entityType);
      }));

    $container->set(
      'entity.manager',
      $entityManager
    );

    \Drupal::setContainer($container);
  }

  public function testGetSetChangedTime() {
    $book = new Book();
    $time = time();
    $book->setChangedTime($time);
    $this->assertEquals($time, $book->getChangedTime(), "Getting/setting the changed time works.");
  }

  public function testGetChangedTimeAcrossTranslations() {

  }

  public function testSetOwner() {

  }

  public function testGetOwner() {

  }

  public function testSetOwnerId() {

  }

  public function testGetOwnerId() {

  }
}

<?php

namespace Drupal\guernsey\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface for defining Book entities.
 *
 * @ingroup guernsey
 */
interface BookInterface extends ContentEntityInterface {

  /**
   * Gets the Book title.
   *
   * @return string
   *   Title of the Book.
   */
  public function getTitle();

  /**
   * Sets the Book title.
   *
   * @param $title
   *
   * @return \Drupal\guernsey\Entity\BookInterface
   *   The called Book entity.
   */
  public function setTitle($title);

  /**
   * Gets the Book authors.
   *
   * @return string[]
   *   Authors of the Book.
   */
  public function getAuthors();

  /**
   * Sets the Book authors.
   *
   * @param string[] $authors
   *
   * @return \Drupal\guernsey\Entity\BookInterface
   *   The called Book entity.
   */
  public function setAuthors(array $authors);

}

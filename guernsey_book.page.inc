<?php

/**
 * @file
 * Contains guernsey_book.page.inc.
 *
 * Page callback for Book entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Book templates.
 *
 * Default template: guernsey_book.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_guernsey_book(array &$variables) {
  // Fetch Book Entity Object.
  $guernsey_book = $variables['elements']['#guernsey_book'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

<?php

namespace Drupal\guernsey\Tests\Unit\Form;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Form\FormState;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Tests\UnitTestCase;
use Drupal\guernsey\Form\BookImportForm;
use Drupal\user\Entity\User;

class BookImportFormTest extends UnitTestCase {

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $container = new ContainerBuilder();

    $translations = $this->getMock(TranslationInterface::class);
    $container->set('string_translation', $translations);

    $user = $this->getMockBuilder(User::class)
      ->disableOriginalConstructor()
      ->getMock();
    $container->set('current_user', $user);

    \Drupal::setContainer($container);
  }

  /**
   * @dataProvider palindromeDataProvider
   */
  public function testValidatePalindrome($value, $pass) {
    $form = array();
    $import_form = new BookImportForm();
    $form_state = new FormState();

    // Set the value and validate it.
    $form_state->setValue('palindrome', $value);
    $import_form->validateForm($form, $form_state);

    if ($pass) {
      $this->assertCount(0, $form_state->getErrors(), "The validation passes for value $value.");
    }
    else {
      $this->assertCount(1, $form_state->getErrors(), "The validation fails for value $value.");
    }
  }

  /**
   * Data provider for testing the palindrome validator.
   */
  public function palindromeDataProvider() {
    return [
      ['asdsa', TRUE],
      ['Was it a car or a cat I saw?', TRUE],
      ['A man, a plan, a canal, Panama!', TRUE],
      ['abc', FALSE],
    ];
  }
}

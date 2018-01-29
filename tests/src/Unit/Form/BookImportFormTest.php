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

    // Mock the string translations.
    $translations = $this->getMock(TranslationInterface::class);
    $container->set('string_translation', $translations);

    // Mock the current user. We'll enhance it later.
    $user = $this->getMockBuilder(User::class)
      ->disableOriginalConstructor()
      ->getMock();
    $container->set('current_user', $user);

    // Set the container.
    \Drupal::setContainer($container);
  }

  public function testFormBuilding() {
    // Test the form building for an anonymous user.
    $import_form = new BookImportForm();
    $form = $import_form->buildForm(array(), new FormState());
    $this->assertArrayNotHasKey('admin_field', $form, "An anonymous user doesn't see the checkbox.");

    // Update the user, give the permission required to see the field. We know
    // the current user is a mock, so we can alter it directly.
    $user = \Drupal::currentUser();
    $user->expects($this->any())
      ->method('hasPermission')
      ->with($this->equalTo('administer book entities'))
      ->will($this->returnValue(TRUE));

    // Build the form again.
    $form = $import_form->buildForm(array(), new FormState());
    $this->assertArrayHasKey('admin_field', $form, "An admin user sees the checkbox.");
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
      $this->assertArrayHasKey('palindrome', $form_state->getErrors(), "The field was correctly marked as having an error.");
    }
  }

  /**
   * Data provider for testing the palindrome validator.
   */
  public function palindromeDataProvider() {
    return [
      ['a', TRUE],
      ['Was it a car or a cat I saw?', TRUE],
      ['A man, a plan, a canal, Panama!', TRUE],
      ['abc', FALSE],
    ];
  }
}

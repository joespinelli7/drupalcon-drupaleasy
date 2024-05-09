<?php

declare(strict_types=1);

namespace Drupal\user_blocker\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a User Blocker form.
 */
final class BlockerForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'user_blocker_blocker';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#description' => $this->t('Enter the username of the user you want to block.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Block User'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    parent::validateForm($form, $form_state);
    $username = $form_state->getValue('username');
    $user = user_load_by_name($username);
    if (empty($user)) {
      $form_state->setError(
        $form['username'],
        $this->t('User %username was not found.', ['%username' => $username])->render()
      );
    } else {
      $current_user = \Drupal::currentUser();
      if ($user->id() == $current_user->id()) {
        $form_state->setError(
          $form['username'],
          $this->t('You cannot block your own account.')->render()
        );
      }
    }
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->messenger()->addStatus($this->t('User has been blocked ❌'));
    $form_state->setRedirect('<front>');
  }

}

<?php

declare(strict_types=1);

namespace Drupal\html5_audio\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'HTML5 Audio' formatter.
 *
 * @FieldFormatter(
 *   id = "html5_audio_formatter",
 *   label = @Translation("HTML5 Audio"),
 *   field_types = {"link"},
 * )
 *
 * field_types specify which fields we want this formatter to work with. In line 17, this will
 * now show up for all link field types.
 */
final class Html5AudioFieldFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings(): array {
    $setting = ['foo' => 'bar'];
    return $setting + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state): array {
    $elements['foo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Foo'),
      '#default_value' => $this->getSetting('foo'),
    ];
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary(): array {
    return [
      $this->t('Foo: @foo', ['@foo' => $this->getSetting('foo')]),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode): array {
    $element = [];

    // Render all field values as part of a single <audio> tag.
    $sources = [];
    foreach ($items as $item) {
      // Get the mime type.
      $mimetype = \Drupal::service('file.mime_type.guesser')->guessMimeType($item->uri);
      $sources[] = [
        'src' => $item->uri,
        'mimetype' => $mimetype,
      ];
    }

    // Put everything in an array for theming.
    $element[] = [
      '#theme' => 'audio_tag',
      '#sources' => $sources,
    ];

    return $element;
  }

}

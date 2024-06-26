<?php

declare(strict_types=1);

namespace Drupal\html5_audio\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Cache\Cache;

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
    $setting = ['foo' => 'bar', 'autoplay' => '0'];

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

    $elements['autoplay'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Autoplay enabled'),
      '#default_value' => $this->getSetting('autoplay'),
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary(): array {
    $summary = [];
    $summary[] = $this->t('Foo: @foo', ['@foo' => $this->getSetting('foo')]);

    $settings = $this->getSettings();
    if ($settings['autoplay']) {
      $summary[] = $this->t('Autoplay is enabled.')->render();
    }
    else {
      $summary[] = $this->t('Autoplay is not enabled.')->render();
    }
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode): array {
    // Initialize render array.
    $element = [];

    // Initialize data for render array. Render all field values as part of a single <audio> tag.
    $sources = [];
//    $cache = [];
    foreach ($items as $item) {
      // Get the mime type.
      $mimetype = \Drupal::service('file.mime_type.guesser')->guessMimeType($item->uri);
      $sources[] = [
        'src' => $item->uri,
        'mimetype' => $mimetype,
      ];
//      $cache[] = [
//        'tags' => ['node:' . $item->getEntity()->id()],
//        'contexts' => ['url'],
//        'max-age' => Cache::PERMANENT,
//      ];
    }

    // Configuration.
    $autoplay = '';
    if ($this->getSetting('autoplay')) {
      $autoplay = 'autoplay';
    }

    // Put everything in an array for theming.
    $element[] = [
      '#theme' => 'audio_tag',
      '#sources' => $sources,
      '#autoplay' => $autoplay,
//      '#cache' => $cache
    ];

    return $element;
  }

}

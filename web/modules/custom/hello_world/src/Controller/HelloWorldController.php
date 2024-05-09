<?php

// PHP declaration to enforce strict types
declare(strict_types=1);

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Hello world! routes.
 * final means no one can extend this class
 */
final class HelloWorldController extends ControllerBase {

  /**
   * Builds the response.
   * __invoke() means this function will immediately be run if this class is ever called on.
   * that way you don't have to specifically call any function inside the .routing.yml file
   */
  public function __invoke(): array {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}

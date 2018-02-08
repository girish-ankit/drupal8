<?php

/**
 * @file
 * Contains \Drupal\custom_menu\Plugin\Menu\LocalAction\CustomLocalAction.
 */

namespace Drupal\custom_menu\Plugin\Menu\LocalAction;

use Drupal\Core\Menu\LocalActionDefault;

/**
 * Defines a local action plugin with a dynamic title.
 */
class CustomLocalAction extends LocalActionDefault {

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->t('My @arg action', array('@arg' => 'dynamic-title'));
  }

}
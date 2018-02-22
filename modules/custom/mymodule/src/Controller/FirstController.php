<?php

/**
 * @file
 * Contains \Drupal\mymodule\Controller\MyModuleController
 */

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;

class FirstController extends ControllerBase {

    public function content() {

//        $ids = \Drupal::entityQuery('block')
//                ->execute();
//
//        print_r($ids);
//        die();
        return array(
            '#type' => 'markup',
            '#markup' => t('This is content of custom page'),
        );
    }

}

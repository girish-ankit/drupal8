<?php

/**
 * @file
 * Contains \Drupal\mymodule\Controller\MyModuleController
 */

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;

class CustomTemplateController extends ControllerBase {

    public function content() {


        return [
            '#theme' => 'custom_page_theme',
            '#page_data' => $this->getData(),
        ];
    }

    private function getData() {

        $data = array(
            'name' => 'Ankit kumar',
            'family' => array(
                array(
                    'firstname' => 'raj -',
                    'lastname' => 'kiran'
                ),
                array(
                    'firstname' => 'abhay',
                    'lastname' => 'kumar'
                ),
                array(
                    'firstname' => 'Kajal',
                    'lastname' => 'kumari'
                )
            ),
            'myarray' => array('name' => 'ankit', 'age' => 30, 'village' => 'Morhar', 'pincode' => '843125'),
            'user' => array('role' => 'admin')
        );
        
        return $data;
    }

}

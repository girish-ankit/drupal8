<?php

/**
 * @file
 * Contains \Drupal\mymodule\Controller\MyModuleController
 */

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;

class FirstController extends ControllerBase {

    public function content() {

	$users = array(
	    array('name' => 'Ankit', age => 31),
	    array('name' => 'Amrit', age => 1),
	    array('name' => 'Raj Kiran', age => 26),
	);

	// demo_template is theme function key used in hook_theme
	// test_var is variable name that is used in template file

	return array(
	    '#theme' => 'demo_template',
	    '#test_var' => $users,
	);
    }

}

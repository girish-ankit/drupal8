<?php

/**
 * @file
 * Contains \Drupal\mymodule\Controller\MyModuleController
 */

namespace Drupal\custom_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\custom_form\ContactStorage;
use Drupal\Core\Url;

class AdminController extends ControllerBase {

    public function content() {

	// Table header.
	$header = array(
	    'id' => t('Id'),
	    'name' => t('Submitter name'),
	    'message' => t('Message'),
	    'operations' => t('Delete'),
	);
	$rows = array();
	$db_logic = \Drupal::service('custom_form.db_logic');
	foreach ($db_logic->getAll() as $id => $content) {
	    // Row with attributes on the row and some of its cells.
	    $path_arr = array();
	    $path_arr['id'] = $content->id;
	    $rows[] = array(
		'data' => array($content->id, $content->name, $content->message, \Drupal::l(t('Edit'), Url::fromRoute('custom_form.edit', $path_arr)), \Drupal::l(t('Delete'), Url::fromRoute('custom_form.delete', $path_arr))),
	    );
	}
	$build = array(
	    '#markup' => \Drupal::l(t('New message'), Url::fromRoute('custom_form.add'))
	);
	$header = array('Id', 'Submitter name', 'Message', 'Edit', 'Delete');
	$build['location_table'] = array(
	    '#theme' => 'table',
	    '#header' => $header,
	    '#rows' => $rows
	);
	$build['pager'] = array(
	    '#type' => 'pager'
	);
	return $build;
    }

}

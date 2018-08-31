<?php

/**
 * @file
 * Contains \Drupal\skipta_career\Controller\CareerLandingPageController
 */

namespace Drupal\skipta_career\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateTimePlus;
use Drupal\Core\Datetime\DrupalDateTime;

class CareerLandingPageController extends ControllerBase {

  public function content() {

    $location = \Drupal::request()->get('location');
    $practice_name = \Drupal::request()->get('practice_name');
    $specialty = \Drupal::request()->get('specialty');
    $interval = \Drupal::request()->get('career_interval');
    $radius = \Drupal::request()->get('radius');


    if (!$location && !$practice_name && !$specialty && !$radius) {
      $default = 0;
      $library = [
          'skipta_career/skipta-career-js',
          'skipta_core/infinite-scroll-js',
          'skipta_career/career-infinite-scroll-js',
          'skipta_career/skipta-career-css'
      ];
    } else {
      $default = 1;
      $library = [];
    }

    return [
        '#theme' => 'career_landing_page',
        '#data' => self::getCareerSearchData($default, $location, $practice_name, $specialty, $interval, $radius),
        '#attached' => [
            'library' => $library
        ],
        '#cache' => [
            'contexts' => ['url.path'],
            'max-age' => 0,
        ],
    ];
  }

  /**
   * Following function pass data to new page controller method
   * 
   * @return type
   */
  public static function getCareerSearchData($default = NULL, $location = NULL, $practice_name = NULL, $specialty = NULL, $interval = NULL, $radius = NULL) {

    // $output .= 'Location: '.$location.' Practice Name: '.$practice_name.' Specialty: '.$specialty.' Radius: '.$radius;
    // echo $output; die();  
    ##### Search Query ################
    $num_rows = 0;
    $data = [];

    $path = drupal_get_path('module', 'skipta_news');
    $ajax_loader_image = $path . '/images/ajax-loader.gif';

    $formatted = time();

    $query = \Drupal::database()->select('node_field_data', 'nfd');
    $query->join('node__field_job_posted_date', 'pd', 'nfd.nid = pd.entity_id');
    $query->fields('nfd', ['nid']);
    $query->distinct();
    $query->condition('nfd.type', 'career');
    $query->condition('nfd.status', 1);
    // condition starts
    if ($location) {
      $query->join('node__field_job_source', 'lfjs', 'nfd.nid = lfjs.entity_id');
      $query->condition('lfjs.field_job_source_value', '%' . $location . '%', 'LIKE');
    }

    if ($practice_name) {
      $query->condition('nfd.title', '%' . $practice_name . '%', 'LIKE');
    }
    if ($specialty) {
      $query->join('node__field_job_speciality', 'fjs', 'nfd.nid = fjs.entity_id');
      $query->condition('fjs.field_job_speciality_value', '%' . $specialty . '%', 'LIKE');
    }
    if ($interval) {
      $min_date = strtotime('-' . $interval . ' days');
      $min_date = date("Y-m-d", $min_date);
      list($y, $m, $d) = explode('-', $min_date);
      $min_date_midnight = mktime(0, 0, 0, $m, $d, $y);
      
      $query->condition('pd.field_job_posted_date_value', $min_date_midnight, '>=');
    }
    
    $moduleHandler = \Drupal::service('module_handler');
    if ($moduleHandler->moduleExists('skipta_admin_actions')){
      $query = skipta_admin_actions_update_stream_query($query, 'node_field_data', 'nfd'); // need to pass table object and query
    }
    
    $query->orderBy('pd.field_job_posted_date_value', 'DESC');

    if ($default) {
      $count_query = $query;
      $num_rows = $count_query->countQuery()->execute()->fetchField();
      //dpm($num_rows);
    }

    $pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(5);
    $results = $pager->execute();

    // dpm((string) $query);
    //dpm($query->arguments());

    while ($row = $results->fetchAssoc()) {
      $data['careers'][$row['nid']] = ['#theme' => 'career_listing_page', '#career_id' => $row['nid']];
    }
    $data['pager'] = ['#type' => 'pager', '#route_name' => 'skipta_career.landing', '#parameters' => ['location' => $location, 'practice_name' => $practice_name, 'specialty' => $specialty, 'interval' => $interval, 'radius' => $radius]];
    $data['ajax_loader_image'] = $ajax_loader_image;
    $data['default'] = $default;
    $data['record_cnt'] = $num_rows;

    ##### Serch Query Ends ############
    //dpm($data);

    return $data;
  }

}

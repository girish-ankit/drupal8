<?php

namespace Drupal\skipta_career\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\Tags;
use Drupal\Component\Utility\Unicode;

/**
 * Defines a route controller for entity autocomplete form elements.
 */
class CareerAutocompleteController extends ControllerBase {

  /**
   * Handler for autocomplete request.
   */
  public function handleAutocomplete(Request $request, $field_name, $count) {
    $results = [];

    // Get the typed string from the URL, if it exists.
    if ($input = $request->query->get('q')) {
      $typed_string = Tags::explode($input);
      $typed_string = Unicode::strtolower(array_pop($typed_string));


      if ($field_name == 'location') {
        $results = $this->getlocation($typed_string, $count);
      }
      if ($field_name == 'practice') {
        $results = $this->getpractice($typed_string, $count);
      }
      if ($field_name == 'specialty') {
        $results = $this->getspecialty($typed_string, $count);
      }
    }

    return new JsonResponse($results);
  }

  private function getlocation($string = NULL, $count = 5) {

    $matches = [];

    $query = \Drupal::database()->select('node__field_job_source', 'fjs');
    $query->distinct();
    $query->fields('fjs', ['field_job_source_value']);
    $query->join('node_field_data', 'n', 'n.nid = fjs.entity_id ');
    $query->condition('fjs.field_job_source_value', '%' . $string . '%', 'LIKE');
    $query->condition('n.status', 1);
    $query->orderBy('fjs.field_job_source_value', 'ASC');
    $query->range(0, $count);
    $result = $query->execute();

    foreach ($result as $row) {
      $matches[] = ['value' => $row->field_job_source_value, 'label' => $row->field_job_source_value];
    }

    return $matches;
  }

  private function getpractice($string = NULL, $count = 5) {
    $matches = [];

    $query = \Drupal::database()->select('node_field_data', 'n');
    $query->distinct();
    $query->fields('n', ['title']);
    $query->condition('n.type', 'career');
    $query->condition('n.title', '%' . $string . '%', 'LIKE');
    $query->condition('n.status', 1);
    $query->orderBy('n.title', 'ASC');
    $query->range(0, $count);
    $result = $query->execute();

    foreach ($result as $row) {
      $matches[] = ['value' => $row->title, 'label' => $row->title];
    }

    return $matches;
  }

  private function getspecialty($string = NULL, $count = 5) {

    $matches = [];

    $query = \Drupal::database()->select('node__field_job_speciality', 'fjs');
    $query->distinct();
    $query->fields('fjs', ['field_job_speciality_value']);
    $query->join('node_field_data', 'n', 'n.nid = fjs.entity_id ');
    $query->condition('fjs.field_job_speciality_value', '%' . $string . '%', 'LIKE');
    $query->condition('n.status', 1);
    $query->orderBy('fjs.field_job_speciality_value', 'ASC');
    $query->range(0, $count);
    $result = $query->execute();

    foreach ($result as $row) {
      $matches[] = ['value' => $row->field_job_speciality_value, 'label' => $row->field_job_speciality_value];
    }

    return $matches;
  }

}

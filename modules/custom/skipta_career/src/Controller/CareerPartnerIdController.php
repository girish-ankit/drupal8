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
class CareerPartnerIdController extends ControllerBase {

  /**
   * Handler for autocomplete request.
   */
  public function handleAutocomplete(Request $request) {
    $matches=[];
    
    $count= 10;

    // Get the typed string from the URL, if it exists.
    if ($input = $request->query->get('q')) {
      $typed_string = Tags::explode($input);
      $typed_string = Unicode::strtolower(array_pop($typed_string));

      $query = \Drupal::database()->select('node_field_data', 'nfd');
      $query->distinct();
      $query->fields('nfd', ['title', 'nid']);
      $query->condition('nfd.title', '%' . $typed_string . '%', 'LIKE');
      $query->condition('nfd.status', 1);
       $query->condition('nfd.type', 'career_partners');
      $query->orderBy('nfd.title', 'ASC');
      $query->range(0, $count);
      $result = $query->execute();

      foreach ($result as $row) {
        $matches[] = ['value' => $row->nid, 'label' => $row->title];
      }
    }

    return new JsonResponse($matches);
  }

 

}

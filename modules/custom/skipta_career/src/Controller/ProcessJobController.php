<?php

/**
 * @file
 * Contains \Drupal\skipta_career\Controller\ProcessJobController
 */

namespace Drupal\skipta_career\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

class ProcessJobController extends ControllerBase {

  public function content() {

    return [
        '#type' => 'markup',
        '#markup' => $this->getProcessJobData(),
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
  private function getProcessJobData() {
    $val = urldecode($_REQUEST['hecArray']);
    $obj = json_decode($val);
    // dpm($data);
    \Drupal::logger('skipta_career')->notice($obj);
    foreach ($obj as $value) {
      $this->setProcessJobData($value);
    }
  }

  private function setProcessJobData($obj) {

    $response = new \StdClass();

    $response->title = (string) $obj->JobTitle;
    $response->additional_information = $obj->AdditionalInformation;
    $response->job_categories = $obj->Category;
    $response->description = $obj->JobDescription;
    $response->contact_information = $obj->ContactInformation;
    $response->position = $obj->JobPosition;
    $response->posted_date = strtotime($obj->PostedDate);
    $response->network_id = $obj->NetworkId;
    if (isset($obj->Url) && !empty($obj->Url)) {
      $response->url = $obj->Url;
    }
    $response->source = $obj->Source;
    $response->city = $obj->City;
    $response->state = $obj->State;
    $response->country = $obj->Country;
    $response->EmployerId = $obj->EmployerId; // drupal backend on used
    $response->employer_name = $obj->EmployerName;
    $response->employer_email = $obj->EmployerEmail;
    $response->job_id = $obj->JobId;
    self::SaveProcessJobData($response);
  }

  public static function SaveProcessJobData($data) {

    $node = Node::create(['type' => 'career']);
    $node->set('title', $data->title);
    $node->set('field_job_additional_information', ['value' => $data->additional_information, 'format' => 'full_html',]);
    $node->set('field_job_categories', [['value' => $data->job_categories]]); // multiple value
    $node->set('field_job_city', $data->city);
    $node->set('field_job_contact_information', ['value' => $data->additional_information, 'format' => 'full_html',]);
    $node->set('field_job_country', $data->country);
    $node->set('field_job_description', ['value' => $data->description, 'format' => 'full_html',]);
    $node->set('field_job_employer_email', $data->employer_email);
    $node->set('field_job_employer_name', $data->employer_name);
    $node->set('field_job_id', $data->job_id);
    $node->set('field_job_network_id', $data->network_id);

    $query = \Drupal::database()->select('node_field_data', 'nfd');
    $query->fields('nfd', ['nid']);
    $query->condition('nfd.type', 'career_partners');
    $query->condition('nfd.status', 1);
    
    $query->condition('nfd.title', '%hec%', 'LIKE');
    $career_partners_nid = $query->execute()->fetchField();
    $node->set('field_job_partner_id', $career_partners_nid);

    $node->set('field_job_position', $data->position);
    $node->set('field_job_posted_date', $data->posted_date);
    $node->set('field_job_state', $data->state);
    $node->set('field_job_url', $data->url);

    $node->set('uid', 1);
    $node->status = 1;
    $node->enforceIsNew();
    $node->save();

    $message = "Node with nid " . $node->id() . " saved!\n";
    \Drupal::logger('skipta_career')->notice($message);
  }

}

<?php

/**
 * @file
 * Contains \Drupal\skipta_career\Controller\CareerLandingPageController
 */

namespace Drupal\skipta_career\Controller;

use Drupal\Core\Controller\ControllerBase;

class ProcessTestController extends ControllerBase {

  public function content() {

    return [
        '#type' => 'markup',
        //  '#markup' => $this->getProcessTestData(),
       // '#markup' => $this->testCareerDataCreation(),
        '#markup' => $this-> demo(),
        '#cache' => [
            'contexts' => ['url.path'],
            'max-age' => 0,
        ],
        '#attached' => [
            'library' => [
                'skipta_career/skipta-career-css'
            ]
        ],
    ];
  }

  private function demo() {
    $user_email = 'ankit'.time();
    $tempstore = \Drupal::service('user.private_tempstore')->get('skipta_career');
    $tempstore->set('user_email', $user_email);
    $tempstore = \Drupal::service('user.private_tempstore')->get('skipta_career');
    $some_data = $tempstore->get('user_email');
    return $some_data;
  }

  private function testCareerDataCreation() {
    $response = new \StdClass();

    $response->title = 'Orthopedics-Career-' . time();
    $response->additional_information = 'Aditional Information';
    // speciality is missing
    /*     * **************************** */
    $response->description = 'This is description of career';
    $response->contact_information = 'Contact Info';
    $response->position = 'Full time';
    $response->country = 'India';
    $response->job_categories = 'Physician / Surgeon.Family Medicine.General Family Medicine';

    $response->posted_date = time();
    $response->network_id = 7;

    $response->url = 'http://www.google.com';

    $response->city = 'Agarkar Nagar, Pune';
    $response->state = 'Maharashtra';
    $response->country = 'India';



    $response->employer_name = 'Ankit Kumar';
    $response->employer_email = 'a@a.com';

    $response->partner_id = 43;
    $response->job_id = '00767';
    \Drupal\skipta_career\Controller\ProcessJobController::SaveProcessJobData($response);
  }

  /**
   * Following function pass data to new page controller method
   * 
   * @return type
   */
  private function getProcessTestData() {
    $limit = 10;
    $data = '';
    $data .= '<div id="career-test">';
    $xmlfile = 'http://drupal-doctorunite.local/xml_to_json/skipta-doctorunitepcp.xml';

    $dom = new \DOMDocument();
    $dom->loadXML(file_get_contents($xmlfile));
    self::json_prepare_xml($dom);
    $sxml = simplexml_load_string($dom->saveXML());
    $json = json_decode(json_encode($sxml));
    $job_object = $json->job;

    //print_r($json->job); die();

    $job_object_cnt = count($job_object);

    if ($job_object_cnt > 0) {
      $i = 0;
      foreach ($job_object as $value) {
        if ($i < $limit) {
          $data .= $this->show_josn_data($value, $i);
        }
        $i++;
      }
    }
    $data .= '</div>';
    return $data;
  }

  public static function json_prepare_xml($domNode) {
    foreach ($domNode->childNodes as $node) {
      if ($node->hasChildNodes()) {
        self::json_prepare_xml($node);
      } else {
        if ($domNode->hasAttributes() && strlen($domNode->nodeValue)) {
          $domNode->setAttribute("nodeValue", $node->textContent);
          $node->nodeValue = "";
        }
      }
    }
  }

  function show_josn_data($value, $i) {

    $value_arr = (array) $value;
    $output = '';

    $output .= '<h1>Record #' . ($i + 1) . '</h1>';
    $output .= '<b>job-id: </b>' . $value_arr['job-id'];
    $output .= '<br /> <b>managed-by-board: </b>' . $value_arr['managed-by-board'];
    $output .= '<br /> <b>date-posted: </b>' . $value_arr['date-posted'];
    $output .= '<br /> <b>title: </b>' . $value_arr['title'];
    $output .= '<br /> <b>hec-job-tagline: </b>' . $value_arr['hec-job-tagline'];
    $output .= '<br /> <b>category: </b>' . $value_arr['category'];
    $output .= '<br /> <b>industry: </b>' . $value_arr['industry'];

    $output .= '<h3>Job Status Info:</h3>';

    $output .= '<b>status-full-time: </b>' . $value_arr['status-full-time'];
    $output .= '<br /> <b>status-part-time: </b>' . $value_arr['status-part-time'];
    $output .= '<br /> <b>status-permanent: </b>' . $value_arr['status-permanent'];
    $output .= '<br /> <b>status-temporary: </b>' . $value_arr['status-temporary'];
    $output .= '<br /> <b>status-full-time: </b>' . $value_arr['status-contract'];
    $output .= '<br /> <b>internal-job-url </b>' . $value_arr['internal-job-url'];
    $output .= '<br /> <b>internal-apply-url: </b>' . $value_arr['internal-apply-url'];

    $output .= '<h3>Employer Info:</h3>';
    $output .= '<br /> <b>employer-id: </b>' . $value_arr['employer-id'];
    $output .= '<br /> <b>employer-job-id: </b>' . $value_arr['employer-job-id'];
    $output .= '<br/><b>employer-name: </b>' . $value_arr['employer-name'];
    $output .= '<br /> <b>employer-email: </b>' . $value_arr['employer-email'];
    $output .= '<br /> <b>employer-email2: </b>' . $value_arr['employer-email2'];

    $employer_additional_info = (array) $value_arr['employer-additional-info'];
    $output .= '<br /> <b>employer-additional-info: </b>' . $employer_additional_info['@attributes']->nodeValue;

    $output .= '<h3>Location Info:</h3>';

    $location = (array) $value_arr['location'];
    $output .= '<b>region-id: </b>' . $location['region-id'];
    $output .= '<br /> <b>city: </b>' . $location['city'];
    $output .= '<br /> <b>state: </b>' . $location['state'];
    $output .= '<br /> <b>country: </b>' . $location['country'];

    $data = @skipta_get_geo_info($address = NULL, $location['city'], $location['state'], $location['country']);
    $output .= '<br /> <b>Address: </b>' . $data['address'];
    $output .= '<br /> <b>Latitude: </b>' . $data['lat'];
    $output .= '<br /> <b>Longitude: </b>' . $data['long'];
    $output .= '<br /> <b>Zip: </b>' . $data['zip'];

    $output .= '<br />';

    $tagline = (array) $value_arr['tagline'];
    $output .= '<br /> <b>tagline: </b>' . $tagline['@attributes']->nodeValue;
    $descriptoin = (array) $value_arr['description'];
    $output .= '<br /> <b>description: </b>' . $descriptoin['@attributes']->nodeValue;
    return $output;

//  echo '<pre>';
//  print_r($value_arr);
//  die();
  }

}

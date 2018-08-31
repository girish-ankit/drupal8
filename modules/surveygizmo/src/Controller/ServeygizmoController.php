<?php

/**
 * @file
 * Contains \Drupal\surveygizmo\Controller\ServeygizmoController
 */

namespace Drupal\surveygizmo\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

class ServeygizmoController extends ControllerBase {

    public function listing() {

        if (empty($_REQUEST['page'])) {
            $page = 1;
        } else {
            $page = $_REQUEST['page'] + 1;
        }

        $config = \Drupal::config('surveygizmoForm.adminsettings');
        $SG_API_KEY = $config->get('SG_API_KEY');
        $SG_API_SECRET = $config->get('SG_API_SECRET');
        $SG_DATA_LIMIT = $config->get('SG_DATA_LIMIT');
        if ($SG_DATA_LIMIT) {
            $limit = $SG_DATA_LIMIT;
        } else {
            $limit = 10;
        }


        try {
            \SurveyGizmo\SurveyGizmoAPI::auth($SG_API_KEY, $SG_API_SECRET);
        } catch (\SurveyGizmo\Helpers\SurveyGizmoException $e) {
            die("Error Authenticating");
        }
        \SurveyGizmo\ApiRequest::setRepeatRateLimitedRequest($limit);

        $filter = new \SurveyGizmo\Helpers\Filter();

        $options = array('page' => $page, 'limit' => $limit);
        $surveys = \SurveyGizmo\Resources\Survey::fetch($filter, $options);
        $total_count = $surveys->total_count;
        $total_pages = $surveys->total_pages;

        pager_default_initialize($total_count, $limit);
        $list = [];
        if ($page == $total_pages) {
            $limit = $total_count % $limit;
        }

        for ($j = 0; $j < $limit; $j++) {
            $url = Url::fromRoute('surveygizmo.detial', array('id' => $surveys->data[$j]->id));
            $detial_path = \Drupal::l(t($surveys->data[$j]->title), $url);
            $list[] = $detial_path;
        }

        $render = [];
        $render[] = [
            '#theme' => 'item_list',
            '#list_type' => 'ul',
            '#items' => $list,
            '#attributes' => ['class' => 'surveygizmo_listing'],
            '#wrapper_attributes' => ['class' => 'container'],
        ];

        $render[] = ['#type' => 'pager'];
        return $render;
    }

    public function detial($id) {

        $config = \Drupal::config('surveygizmoForm.adminsettings');
        $SG_API_KEY = $config->get('SG_API_KEY');
        $SG_API_SECRET = $config->get('SG_API_SECRET');

        try {
            \SurveyGizmo\SurveyGizmoAPI::auth($SG_API_KEY, $SG_API_SECRET);
        } catch (\SurveyGizmo\Helpers\SurveyGizmoException $e) {
            testLog("Error Authenticating", $e);
            die;
        }
        \SurveyGizmo\ApiRequest::setRepeatRateLimitedRequest(10);

        if ($id) {
            $survey_id = $id;
        } else {
            echo 'wrong request';
            die();
        }
        $data = '';
        $survey = \SurveyGizmo\Resources\Survey::get($survey_id);
//echo '<pre>';
//print_r($survey); die();
        $id = $survey->id;
        $title = $survey->title;
        $internal_title = $survey->internal_title;
        $links = $survey->links->campaign;
        $created_on = strtotime($survey->created_on);

        $data .= '<h1>' . $title . '</h1>';
        $data .= '<p><b>Created On: </b>' . date('d/m/Y H:i:s', $created_on) . '</p>';
//print_r($survey->pages[0]->questions);

        $i = 1;
        $output = '';

        foreach ($survey->pages[0]->questions as $key => $value) {
            //  echo '*************';
            // echo '<pre>';
            // print_r($value);
            $question_id = $value->id;
            $question_title = $value->title->English;
            $question_type = $value->type;
            $is_required = '';
            if ($value->properties->required) {
                $is_required = '*';
            }

            $options = array();

            $output .= '<br /><br /><b> Question:' . $i . $is_required . ': ' . $question_title . '</b><br />';

            if ($question_type == 'TEXTBOX') {
                $output .= '<input type="' . $question_type . '" name="" value="" /><br>';
            } else {
                foreach ($value->options as $key_in => $value_in) {
                    //  $options[$value_in->id] = $value_in->value;
                    $output .= '<input type="' . $question_type . '" name="" value="' . $value_in->id . '" />' . $value_in->value . '<br>';
                }
            }

            $i++;
        }

        $data .= $output;
        $data .= '<br />';
        $data .= '<input type="hidden" name="" value="' . $links . '" />';
        $data .= '<input type="submit" />';

        return array(
            '#type' => 'markup',
            '#markup' => $data,
        );
    }

}

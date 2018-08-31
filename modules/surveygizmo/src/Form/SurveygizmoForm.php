<?php

/**
 * @file  
 * Contains \Drupal\surveygizmo\Form\SurveygizmoForm.  
 */

namespace Drupal\surveygizmo\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class SurveygizmoForm extends FormBase {

    /**
     * {@inheritdoc}  
     */
    public function getFormId() {
        return 'surveygizmo_survey_form';
    }

    /**
     * {@inheritdoc}  
     */
    public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {

        $config = \Drupal::config('surveygizmoForm.adminsettings');
        $SG_API_KEY = $config->get('SG_API_KEY');
        $SG_API_SECRET = $config->get('SG_API_SECRET');

        try {
            \SurveyGizmo\SurveyGizmoAPI::auth($SG_API_KEY, $SG_API_SECRET);
        } catch (\SurveyGizmo\Helpers\SurveyGizmoException $e) {
            die("Error Authenticating");
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
        $id = $survey->id;
        $title = $survey->title;
        $internal_title = $survey->internal_title;
        $links = $survey->links->campaign;
        $created_on = strtotime($survey->created_on);

        $form['#title'] = $title;

        $form['servery_created'] = [
            '#type' => 'item',
            '#title' => t('Created On: '),
            '#markup' => date('d/m/Y H:i:s', $created_on),
        ];


        $url = Url::fromUri($links);
        $form_url = \Drupal::l(t($title), $url);
        $form['servery_link'] = [
            '#type' => 'item',
            '#title' => t(''),
            '#markup' => $form_url,
        ];
        $form['survery_id'] = array(
            '#type' => 'hidden',
            '#value' => $id,
        );
        $form['survery_url'] = array(
            '#type' => 'hidden',
            '#value' => $links,
        );

        $i=1;

        foreach ($survey->pages[0]->questions as $key => $value) {
            //  echo '*************';
            // echo '<pre>';
            // print_r($value);
            $question_id = $value->id;
            $question_title = $value->title->English;
            $question_type = $value->type;
            $is_required = FALSE;
            if ($value->properties->required) {
                $is_required = TRUE;
            }


            $options = [];

            $field_type = ['TEXTAREA' => 'textarea', 'TEXTBOX' => 'textfield', 'EMAIL' => 'email', 'CHECKBOX' => 'checkboxes', 'RADIO' => 'radios', 'SELECT' => 'select', 'MENU' => 'select'];


            if ($field_type[$question_type] == 'checkboxes' || $field_type[$question_type] == 'radios') {

                foreach ($value->options as $key_in => $value_in) {
                    $options[$value_in->id] = $value_in->value;
                }
                $form['f-' . $question_id] = array(
                    '#type' => $field_type[$question_type],
                    '#title' => t($i.'. '.$question_title),
                    '#required' => $is_required,
                    '#options' => $options,
                );
                 $i++;
            }else if($field_type[$question_type] == 'select'){
                dpm($value->options);
                 foreach ($value->options as $key_in => $value_in) {
                    $options[$value_in->id] = $value_in->value;
                }
                 $form['f-' . $question_id] = array(
                    '#type' => $field_type[$question_type],
                    '#title' => t($i.'. '.$question_title),
                    '#required' => $is_required,
                    '#options' => $options,
                );
                 $i++;
            }
            else {
                $form['f-' . $question_id] = array(
                    '#type' => $field_type[$question_type],
                    '#title' => t($i.'. '.$question_title),
                    '#required' => $is_required,
                );
                 $i++;
            }
          
        }


        $form['actions'] = array('#type' => 'actions');
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => t('Submit'),
        );
        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        
    }

}

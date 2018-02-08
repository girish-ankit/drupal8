<?php

/**
 * @file
 * Contains Drupal\ajax_example\AjaxExampleForm
 */

namespace Drupal\custom_menu\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

class AjaxExampleForm extends FormBase {

    public function getFormId() {
        return 'ajax_example_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['check_number'] = array(
            '#type' => 'textfield',
            '#title' => 'Enter Number',
            '#description' => 'check a number is odd or even',
            '#prefix' => '<div id="number-check-result"></div>',
            '#ajax' => array(
                'callback' => '::checkNumberValidation',
                'effect' => 'fade',
                'event' => 'change',
                'progress' => array(
                    'type' => 'throbber',
                    'message' => NULL,
                ),
            ),
        );
        return $form;
    }

    public function checkNumberValidation(array $form, FormStateInterface $form_state) {
        $ajax_response = new AjaxResponse();
        $check_number = $form_state->getValue('check_number');

        // Check number
        if(!is_numeric ($check_number)){
            $text = '<h3>You have not entered a number</h3>';
        }elseif ($check_number < 0) {
            $text = '<h3>You have entered a negative number</h3>';
        } 
        elseif ($check_number%2 ==0) {
            $text = '<h3>You have entered even number</h3>';
        } else {
            $text = '<h3>You entered an odd number</h3>';
        }
        $ajax_response->addCommand(new HtmlCommand('#number-check-result', $text));
        return $ajax_response;
    }
    
    public function validateForm(array &$form, FormStateInterface $form_state) {
        
    }
    
    public function submitForm(array &$form, FormStateInterface $form_state) {
        
    }

}

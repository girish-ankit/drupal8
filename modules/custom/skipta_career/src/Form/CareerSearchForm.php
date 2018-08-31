<?php

/**
 * @file
 * Contains \Drupal\skipta_career\Form\ResumeForm.
 */

namespace Drupal\skipta_career\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\AlertCommand;
use Drupal\skipta_career\Controller\CareerLandingPageController;

class CareerSearchForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'career_search_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $cnt = 10;

    $form['career_location'] = [
        '#type' => 'textfield',
        '#title' => t(''),
        '#attributes' => ['placeholder' => t('Location')],
        '#autocomplete_route_name' => 'skipta_career.autocomplete',
        '#autocomplete_route_parameters' => ['field_name' => 'location', 'count' => $cnt],
    ];
    $form['career_practice_name'] = [
        '#type' => 'textfield',
        '#title' => t(''),
        '#attributes' => ['placeholder' => t('Practice Name')],
        '#autocomplete_route_name' => 'skipta_career.autocomplete',
        '#autocomplete_route_parameters' => ['field_name' => 'practice', 'count' => $cnt],
    ];
    $form['career_specialty'] = [
        '#type' => 'textfield',
        '#title' => t(''),
        '#attributes' => ['placeholder' => t('Specialty')],
        '#autocomplete_route_name' => 'skipta_career.autocomplete',
        '#autocomplete_route_parameters' => ['field_name' => 'specialty', 'count' => $cnt],
    ];
    $form['career_interval'] = [
        '#type' => 'select',
        '#title' => (''),
        '#options' => [
            '0' => t('Select time interval '),
            '1' => t('In last 1 day'),
            '7' => t('In last 7 days'),
            '14' => t('In last 14 days'),
            '30' => t('In last 30 days'),
        ]
    ];
    $form['career_radius'] = [
        '#type' => 'range',
        '#title' => t(''),
        '#attributes' => [
            'min' => 50,
            'max' => 500,
            'step' => 50,
            'value' => 50,
            'title' => '50 miles'
        ],
        '#prefix' => '<div id="redius-info"></div>'
    ];
    
    $form['actions']['reset'] = [
        '#type' => 'button',
        '#value' => $this->t('Reset'),
        '#button_type' => 'primary',
    ];

    $form['actions']['search_title'] = [
        '#type' => 'button',
        '#value' => $this->t('Search Title'),
        '#button_type' => 'primary',

        '#attributes' => ['class' => ['use-ajax-submit', 'btn-success']],

        '#ajax' => [
            'wrapper' => 'skipta-career-wrapper',
            'callback' => '::carrer_search_ajax_search_title'
        ],
    ];

    
//    $form['actions']['save'] = [
//        '#type' => 'button',
//        '#value' => $this->t('Save'),
//        '#button_type' => 'primary',
//    ];

    $form['#theme'] = 'career_search_form';
    return $form;
  }

  public function carrer_search_ajax_search_title(array $form, FormStateInterface &$form_state) {

    $response = new AjaxResponse();

    $location = $form_state->getValue('career_location');
    $practice_name = $form_state->getValue('career_practice_name');
    $specialty = $form_state->getValue('career_specialty');
    $interval = $form_state->getValue('career_interval');
    $radius = $form_state->getValue('career_radius');
    $default = 1;

    $output = [
        '#theme' => 'career_landing_page',
        '#data' => CareerLandingPageController::getCareerSearchData($default, $location, $practice_name, $specialty, $interval, $radius),
        '#cache' => [
            'contexts' => ['url.path'],
            'max-age' => 0,
        ],
    ];



    $response->addCommand(new HtmlCommand('#skipta-career-wrapper', $output));
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
  }

}

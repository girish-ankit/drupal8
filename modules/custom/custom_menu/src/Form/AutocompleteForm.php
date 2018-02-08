<?php

/**
 * @file
 * Contains \Drupal\custom_menu\Form\ResumeForm.
 */

namespace Drupal\custom_menu\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class AutocompleteForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'autocomplete_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        /**
         * 1>.'#autocomplete_route_name': for passing route name of callback URL 
         * to be used by autocomplete Javascript Library.
         * 
         * 2>. '#autocomplete_route_parameters':for passing array of arguments to 
         * be passed to autocomplete handler.
         */
        $form['name'] = array(
            '#type' => 'textfield',
            '#autocomplete_route_name' => 'custom_menu.autocomplete',
            '#autocomplete_route_parameters' => array('field_name' => 'name', 'count' => 10),
        );


        return $form;
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

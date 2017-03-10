<?php

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class DeleteContactForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
	return 'add_contact_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
	$form['candidate_name'] = array(
	    '#type' => 'textfield',
	    '#title' => t('Candidate Name:'),
	    '#required' => TRUE,
	);
	$form['submit'] = array(
	    '#type' => 'submit',
	    '#value' => $this->t('Export'),
	);
	return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
	if (strlen($form_state->getValue('candidate_number')) < 10) {
	    $form_state->setErrorByName('candidate_number', $this->t('Mobile number is too short.'));
	}
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
	// drupal_set_message($this->t('@can_name ,Your application is being submitted!', array('@can_name' => $form_state->getValue('candidate_name'))));
	foreach ($form_state->getValues() as $key => $value) {
	    drupal_set_message($key . ': ' . $value);
	}
    }

}

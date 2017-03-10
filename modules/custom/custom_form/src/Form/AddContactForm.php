<?php

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class AddContactForm extends FormBase {

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
	$current_path = \Drupal::service('path.current')->getPath();
	$path_args = explode('/', $current_path);
	if ($path_args[5]) {
	    $db_logic = \Drupal::service('custom_form.db_logic');
	    $data = $db_logic->getById($path_args[5]);
	    $name_default_value = $data[0]->name;
	    $message_default_value = $data[0]->message;
	} else {
	    $name_default_value = '';
	    $message_default_value = '';
	}

	$form['name'] = array(
	    '#type' => 'textfield',
	    '#title' => t('Name'),
	    '#default_value' => $name_default_value,
	);
	$form['message'] = array(
	    '#type' => 'textarea',
	    '#title' => t('Message'),
	    '#default_value' => $message_default_value,
	);
	$form['actions'] = array('#type' => 'actions');
	$form['actions']['submit'] = array(
	    '#type' => 'submit',
	    '#value' => t('Add'),
	);
	return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
	if (strlen($form_state->getValue('name')) < 0) {
	    $form_state->setErrorByName('name', $this->t('Name is required field'));
	}
	if (strlen($form_state->getValue('message')) < 10) {
	    $form_state->setErrorByName('message', $this->t('Message  Must be greater than 10 characters'));
	}
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {

	$name = $form_state->getValue('name');
	$message = $form_state->getValue('message');

	$db_logic = \Drupal::service('custom_form.db_logic');

	if ($db_logic->add($name, $message)) {
	    drupal_set_message('Contact form has been submited');
	    $url = Url::fromRoute('custom_form.list');
	    $form_state->setRedirectUrl($url);
	}
    }

}

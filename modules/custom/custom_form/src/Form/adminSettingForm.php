<?php

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class adminSettingForm extends ConfigFormBase {

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
	return array(
	    'welcome.adminsettings',
	);
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
	return 'admin_setting_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
	$config = $this->config('welcome.adminsettings');

	$form['welcome_message'] = array(
	    '#type' => 'textarea',
	    '#title' => $this->t('Welcome message'),
	    '#description' => $this->t('Welcome message display to users when they login'),
	    '#default_value' => $config->get('welcome_message'),
	);
	return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
	parent::submitForm($form, $form_state);

	$this->config('welcome.adminsettings')
		->set('welcome_message', $form_state->getValue('welcome_message'))
		->save();
    }

}

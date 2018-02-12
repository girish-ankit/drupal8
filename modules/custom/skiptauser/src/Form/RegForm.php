<?php
/**
 * @file
 * Contains \Drupal\skiptauser\Form\RegForm.
 */
namespace Drupal\skiptauser\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class RegForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'reg_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['first_name'] = array(
      '#type' => 'textfield',
      '#title' => t('First Name:'),
      '#required' => TRUE,
    );
	
	$form['last_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Last Name:'),
      '#required' => FALSE,
    );

    $form['email'] = array(
      '#type' => 'email',
      '#title' => t('Email ID:'),
      '#required' => TRUE,
    );

    $form['npi_number'] = array (
      '#type' => 'number',
      '#title' => t('NPI Number'),
    );

	$form['reg_options'] = array(
		//'#type' => 'checkbox',
		//'#title' => t('I would like to register using my State License Number.'),
		'#type' => 'radios',
		'#options' => array('slicense' => t('I would like to register using my State License Number.'), 'nonpi' => t('I don\'t have my NPI number available.') , 'mstudent' => t('I am a medical student or resident.')),
    );
	
	$form['state'] = array(
		'#title' => t('State'),
		'#description' => "",
		'#type' => 'select',
		'#options' => $this->stateOptions(),
		'#default_value' => $state,
		'#states' => array(
			'visible' => array(
				':input[name="reg_options"]' => array('value' => 'slicense'),
			),
			'required' => array(
				':input[name="reg_options"]' => array('value' => 'slicense'),
			),
		),
	);
	
	$form['license_number'] = array (
		'#type' => 'number',
		'#title' => t('License Number'),
		'#states' => array(
			'visible' => array(
				':input[name="reg_options"]' => array('value' => 'slicense'),
			),
			'required' => array(
				':input[name="reg_options"]' => array('value' => 'slicense'),
			),
		),
    );
	
	$form['medical_school'] = array(
		'#type' => 'textfield',
		'#title' => t('Medical School'),
		'#states' => array(
			'visible' => array(
				':input[name="reg_options"]' => array('value' => 'mstudent'),
			),
			'required' => array(
				':input[name="reg_options"]' => array('value' => 'mstudent'),
			),
		),
    );
	
	$form['graduation_year'] = array (
		'#type' => 'number',
		'#title' => t('Graduation Year'),
		'#states' => array(
			'visible' => array(
				':input[name="reg_options"]' => array('value' => 'mstudent'),
			),
			'required' => array(
				':input[name="reg_options"]' => array('value' => 'mstudent'),
			),
		),
    );
	
	$form['pass_fields'] = array(
		'#type' => 'password_confirm',
		'#description' => t('Enter the same password in both fields'),
		'#size' => 32,
		'#states' => array(
			'invisible' => array(
				':input[name="reg_options"]' => array('value' => 'noapi'),
			),
		),
	);

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  public function stateOptions() {
  $state_options = array(
  '' => t('Please Select State'),
  'AL' => t('Alabama'),
  'AK' => t('Alaska'),
  'AZ' => t('Arizona'),
  'AR' => t('Arkansas'),
  'CA' => t('California'),
  'CO' => t('Colorado'),
  'CT' => t('Connecticut'),
  'DE' => t('Delaware'),
  'DC' => t('District of Columbia'),
  'FL' => t('Florida'),
  'GA' => t('Georgia'),
  'HI' => t('Hawaii'),
  'ID' => t('Idaho'),
  'IL' => t('Illinois'),
  'IN' => t('Indiana'),
  'IA' => t('Iowa'),
  'KS' => t('Kansas'),
  'KY' => t('Kentucky'),
  'LA' => t('Louisiana'),
  'ME' => t('Maine'),
  'MD' => t('Maryland'),
  'MA' => t('Massachusetts'),
  'MI' => t('Michigan'),
  'MN' => t('Minnesota'),
  'MS' => t('Mississippi'),
  'MO' => t('Missouri'),
  'MY' => t('Montana'),
  'NE' => t('Nebraska'),
  'NV' => t('Nevada'),
  'NH' => t('New Hampshire'),
  'NJ' => t('New Jersey'),
  'NM' => t('New Mexico'),
  'NY' => t('New York'),
  'NC' => t('North Carolina'),
  'ND' => t('North Dakota'),
  'OH' => t('Ohio'),
  'OK' => t('Oklahoma'),
  'OR' => t('Oregon'),
  'PA' => t('Pennsylvania'),
  'RI' => t('Rhode Island'),
  'SC' => t('South Carolina'),
  'SD' => t('South Dakota'),
  'TN' => t('Tennessee'),
  'TX' => t('Texas'),
  'UT' => t('Utah'),
  'VT' => t('Vermont'),
  'VA' => t('Virginia'),
  'WA' => t('Washington'),
  'WV' => t('West Virginia'),
  'WI' => t('Wisconsin'),
  'WY' => t('Wyoming'),
);
return $state_options;
  }
  /**
   * {@inheritdoc}
   */
    public function validateForm(array &$form, FormStateInterface $form_state) {

      if (strlen($form_state->getValue('candidate_number')) < 10) {
        //$form_state->setErrorByName('candidate_number', $this->t('Mobile number is too short.'));
      }

    }

  /**
   * {@inheritdoc}
   */
	public function submitForm(array &$form, FormStateInterface $form_state) {
		// drupal_set_message($this->t('@can_name ,Your application is being submitted!', array('@can_name' => $form_state->getValue('candidate_name'))));
		foreach ($form_state->getValues() as $key => $value) {
			drupal_set_message($key . ': ' . $value);
		}
	
		$language = \Drupal::languageManager()->getCurrentLanguage()->getId();
		$user = \Drupal\user\Entity\User::create();

		//Mandatory settings
		$user->setPassword($form_state->getValue('pass_fields'));
		$user->enforceIsNew();
		$user->setEmail($form_state->getValue('email'));
		$user->setUsername($form_state->getValue('email')); //This username must be unique and accept only a-Z,0-9, - _ @ .

		//Optional settings
		$user->set("init", 'email');
		$user->set("langcode", $language);
		$user->set("preferred_langcode", $language);
		$user->set("preferred_admin_langcode", $language);
		//$user->set("setting_name", 'setting_value');
		//For User profile 
		$user->set("field_first_name", $form_state->getValue('first_name'));
		$user->set("field_last_name", $form_state->getValue('last_name'));
		$user->set("field_npi_number", $form_state->getValue('npi_number'));
		$user->set("field_license_number", $form_state->getValue('license_number'));
		$user->set("field_medical_school", $form_state->getValue('medical_school'));
		$user->set("field_state", $form_state->getValue('state'));

		$user->activate();

		$res = $user->save();
		
		
		_user_mail_notify('register_admin_created',$user);
   }
}
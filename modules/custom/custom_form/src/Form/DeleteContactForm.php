<?php

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Defines a confirmation form for deleting mymodule data.
 */
class DeleteContactForm extends ConfirmFormBase {

    /**
     * The ID of the item to delete.
     *
     * @var string
     */
    protected $id;

    /**
     * {@inheritdoc}.
     */
    public function getFormId() {
	return 'delete_contact_form';
    }

    /**
     * {@inheritdoc}
     */
    public function getQuestion() {
	//the question to display to the user.
	return t('Do you want to delete %id?', array('%id' => $this->id));
    }

    /**
     * {@inheritdoc}
     */
    public function getCancelUrl() {
	//this needs to be a valid route otherwise the cancel link won't appear
	return new Url('custom_form.list');
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription() {
	//a brief desccription
	return t('Only do this if you are sure!');
    }

    /**
     * {@inheritdoc}
     */
    public function getConfirmText() {
	return $this->t('Delete it Now!');
    }

    /**
     * {@inheritdoc}
     */
    public function getCancelText() {
	return $this->t('Cancel');
    }

    /**
     * {@inheritdoc}
     *
     * @param int $id
     *   (optional) The ID of the item to be deleted.
     */
    public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
	$this->id = $id;
	return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {

	$db_logic = \Drupal::service('custom_form.db_logic');
	$db_logic->delete($this->id);
	drupal_set_message('Data has been deleted related to id: ' . $this->id);
	$url = Url::fromRoute('custom_form.list');
	$form_state->setRedirectUrl($url);
    }

}

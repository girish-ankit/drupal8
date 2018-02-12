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
            $id_default_value = $data[0]->id;
            $name_default_value = $data[0]->name;
            $message_default_value = $data[0]->message;
        } else {
            $id_default_value = '';
            $name_default_value = '';
            $message_default_value = '';
        }

        $form['id'] = array(
            '#type' => 'hidden',
            '#value' => $id_default_value,
        );

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
        if (strlen($form_state->getValue('name')) < 1) {
            $form_state->setErrorByName('name', $this->t('Name is required field'));
        }
        if (strlen($form_state->getValue('message')) < 10) {
            $form_state->setErrorByName('message', $this->t('Message  Must be greater than 10 characters'));
        }
        if (strlen($form_state->getValue('message')) > 255) {
            $form_state->setErrorByName('message', $this->t('Message  Must not be greater than 255 characters'));
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $db_logic = \Drupal::service('custom_form.db_logic');
        $id = $form_state->getValue('id');
        $name = $form_state->getValue('name');
        $message = $form_state->getValue('message');
        $i = 0;
        if ($id) {
            $i = 1;
            $db_logic->update($id, $name, $message);
        } else {
            $i = 1;
            $db_logic->add($name, $message);
        }


        if ($i == 1) {
            drupal_set_message('Contact form has been submited');
            $url = Url::fromRoute('custom_form.list');
            $form_state->setRedirectUrl($url);
        }
    }

}

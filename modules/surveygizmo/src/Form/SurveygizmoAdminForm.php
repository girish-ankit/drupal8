<?php

/**
 * @file  
 * Contains Drupal\surveygizmo\Form\SurveygizmoAdminForm.  
 */

namespace Drupal\surveygizmo\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SurveygizmoAdminForm extends ConfigFormBase {

    /**
     * {@inheritdoc}  
     */
    protected function getEditableConfigNames() {
        return [
            'SurveygizmoAdminForm.adminsettings',
        ];
    }

    /**
     * {@inheritdoc}  
     */
    public function getFormId() {
        return 'surveygizmo_admin_form';
    }

    /**
     * {@inheritdoc}  
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $config = $this->config('SurveygizmoAdminForm.adminsettings');

        $form['SG_API_KEY'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Surveygizmo API KEY'),
            '#description' => $this->t('Please enter surveygizmo api key'),
            '#default_value' => $config->get('SG_API_KEY'),
        ];

        $form['SG_API_SECRET'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Surveygizmo API SECRET'),
            '#description' => $this->t('Please enter surveygizmo api key'),
            '#default_value' => $config->get('SG_API_SECRET'),
        ];
        
         $form['SG_DATA_LIMIT'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Surveygizmo API Question'),
            '#description' => $this->t('Please enter number of record fetch'),
            '#default_value' => $config->get('SG_DATA_LIMIT'),
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}  
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        parent::submitForm($form, $form_state);

        $this->config('SurveygizmoAdminForm.adminsettings')
                ->set('SG_API_KEY', $form_state->getValue('SG_API_KEY'))
                ->set('SG_API_SECRET', $form_state->getValue('SG_API_SECRET'))
                ->set('SG_DATA_LIMIT', $form_state->getValue('SG_DATA_LIMIT'))
                ->save();
    }

}

<?php

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;

class FileForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'file_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form = array(
            '#attributes' => array('enctype' => 'multipart/form-data'),
        );

        $form['file_upload_details'] = array(
            '#markup' => t('<b>The File</b>'),
        );

        $validators = array(
            'file_validate_extensions' => array('gif png jpg jpeg'),
        );
        $form['single_file'] = array(
            '#type' => 'managed_file',
            '#name' => 'name_single_file',
            '#multiple' => FALSE,
            '#required' => TRUE,
            '#title' => t('Single File'),
            '#size' => 20,
            '#description' => t('Allowed extensions: gif png jpg jpeg'),
            '#upload_validators' => $validators,
            '#theme' => 'image_widget',
            '#preview_image_style' => 'medium',
            '#upload_location' => 'public://',
        );

        $form['mainslider_slide_one'] = [
            '#title' => t('Multiple Files'),
            '#type' => 'managed_file',
            '#name' => 'multi_file',
            '#multiple' => TRUE,
            '#required' => TRUE,
            '#description' => t('Allowed extensions: gif png jpg jpeg pdf xls doc'),
            '#upload_validators' => [
                'file_validate_is_image' => array(),
                'file_validate_extensions' => array('gif png jpg jpeg pdf xls doc'),
                'file_validate_size' => array(25600000)
            ],
            '#upload_location' => 'public://module-images/home-slider-images/',
           '#theme-wrapper' => ['ankit_file_link'],
        ];

        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Save'),
            '#button_type' => 'primary',
        );
        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {

        if ($form_state->getValue('my_file') == NULL) {
            $form_state->setErrorByName('my_file', $this->t('File.'));
        }

        $managedFileId_imageOne = $form_state->getValue(['mainslider_slide_one' => 'image_dir']);

        if (empty($managedFileId_imageOne)) {
            $form_state->setErrorByName('test', 'No image found for image one');
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        // Need to get file details i.e upload file name, size etc.

        dpm($form_state->getValue('my_file'));

        // Display success message.
        drupal_set_message('AMS file successfully uploaded.');

        // Redirect.
//    $form_state->setRedirect('my_module._______');

        /**
         * 
         */
        $image = $form_state->getValue('image');
        $file = File::load($image[0]);
        $file->setPermanent();
        $file->save();
    }

    public static function saveFile($fid, $moduleName, $fileType) {

        if (isset($fid) && is_string($fid)) {

            /**
             * @var $file File
             */
            $file = File::load($fid);

            // save the file
            /**
             * @var $file_usage DatabaseFileUsageBackend
             */
            $file_usage = \Drupal::service('file.usage');
            $file_usage->add($file, $moduleName, $fileType, 1); // or $themeName

            return true;
        }

        return false;
    }

}

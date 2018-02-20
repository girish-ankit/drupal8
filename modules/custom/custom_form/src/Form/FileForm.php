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
            'file_validate_extensions' => array('gif png jpg jpeg pdf xls doc'),
        );
       

        $form['File'] = array(
            '#type' => 'managed_file',
            '#title' => t('Choose  File'),
            '#upload_location' => 'public://MyFilesFolder/',
            '#multiple' => TRUE,
           // '#default_value' => $entity->get('File')->value,
            '#default_value'=> 25,
            '#description' => t('upload file'),
            '#upload_validators' => $validators,
            '#theme'=> 'abc_thumb_upload',
            '#states' => array(
                'visible' => array(
                    ':input[name="File_type"]' => array('value' => t('Upload Your File')),
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

    public function validateForm(array &$form, FormStateInterface $form_state) {
        /*
          if ($form_state->getValue('my_file') == NULL) {
          $form_state->setErrorByName('my_file', $this->t('File.'));
          }

          $managedFileId_imageOne = $form_state->getValue(['mainslider_slide_one' => 'image_dir']);

          if (empty($managedFileId_imageOne)) {
          $form_state->setErrorByName('test', 'No image found for image one');
          }
         * 
         */
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

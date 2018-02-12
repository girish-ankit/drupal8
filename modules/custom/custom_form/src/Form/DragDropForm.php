<?php

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class DragDropForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'drag_drop_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        $form = array();

        $form['mytable']['#type'] = 'table';

        $form['mytable']['#header'] = array(
            t('Label'),
            t(''),
            t('Weight'),
            t('Operations')
        );

        $form['mytable']['#empty'] = t('There are no items yet. <a href="@add-url">Add an item.</a>', array(
            '@add-url' => Url::fromRoute('custom_form.add'),
        ));


        $form['mytable']['#tabledrag'] = array(
            array(
                'action' => 'order',
                'relationship' => 'sibling',
                'group' => 'mytable-order-weight',
            ),
        );


        $db_logic = \Drupal::service('custom_form.db_logic');

        foreach ($db_logic->getAll() as $id => $content) {
            // TableDrag: Mark the table row as draggable.
            $form['mytable'][$id]['#attributes']['class'][] = 'draggable';
            // TableDrag: Sort the table row according to its existing/configured weight.
            $form['mytable'][$id]['#weight'] = $content->id;

            // Some table columns containing raw markup.
            $form['mytable'][$id]['label'] = array(
                '#plain_text' => $content->name,
            );
            $form['mytable'][$id]['id'] = array(
                //'#plain_text' => $entity->ref,
                '#type' => 'number',
                '#title' => "",
                '#default_value' => $content->id,
                '#disabled' => TRUE,
                '#access' => FALSE,
            );

//            $form['mytable'][$id]['id'] = array(
//                '#type' => 'hidden',
//                '#default_value'=> $content->id
//            );
//             $form['mytable'][$id]['name'] = array(
//                '#type' => 'hidden',
//                '#default_value'=> $content->name
//            );
            // TableDrag: Weight column element.
            // NOTE: The tabledrag javascript puts the drag handles inside the first column,
            // then hides the weight column. This means that tabledrag handle will not show
            // if the weight element will be in the first column so place it further as in this example.
            $form['mytable'][$id]['weight'] = array(
                '#type' => 'weight',
                '#title' => t('Weight for @title', array('@title' => $content->name)),
                '#title_display' => 'invisible',
                '#default_value' => $content->id,
                // Classify the weight element for #tabledrag.
                '#attributes' => array('class' => array('mytable-order-weight')),
            );

            // Operations (dropbutton) column.
            $form['mytable'][$id]['operations'] = array(
                '#type' => 'operations',
                '#links' => array(),
            );
            $form['mytable'][$id]['operations']['#links']['edit'] = array(
                'title' => t('Edit'),
                'url' => Url::fromRoute('custom_form.edit', array('id' => $content->id)),
            );
            $form['mytable'][$id]['operations']['#links']['delete'] = array(
                'title' => t('Delete'),
                'url' => Url::fromRoute('custom_form.delete', array('id' => $content->id)),
            );
        }
        $form['actions'] = array('#type' => 'actions');
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => t('Save changes'),
                // TableSelect: Enable the built-in form validation for #tableselect for
                // this form button, so as to ensure that the bulk operations form cannot
                // be submitted without any selected items.
                // '#tableselect' => TRUE,
        );
        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
        
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $val = $form_state->getValue('mytable');
        ;
        print_r($val);
        die();
    }

}

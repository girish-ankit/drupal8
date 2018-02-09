<?php

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class TabularForm extends FormBase {

    public function getFormId() {
        return 'tabular_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        $vocabulary_name = 'states';
        $results = $this->getTaxonomyData($vocabulary_name);
        $data = array();

        foreach ($results as $result) {
          
            $data[$result->tid] = [
                'Termid' => $result->tid, // 'Termid' was the key used in the header
                'Termname' => $result->name, // 'Termname' was the key used in the header
            ];
        }

        $header = [
            'Termid' => t('Term id'),
            'Termname' => t('Term name'),
            
        ];

        $form = array();

        $form['table'] = [
            '#type' => 'tableselect',
            '#header' => $header,
            '#options' => $data,
            '#empty' => t('No Term found'),
        ];
        
        $form['submit']= array('#type'=>'submit', '#value' => 'Submit');
        // Finally add the pager.
        $form['pager'] = array(
            '#type' => 'pager'
        );

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
        
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        
    }

    private function getTaxonomyData($vid) {
        $query = \Drupal::database()->select('taxonomy_term_field_data', 'td');
        $query->fields('td', ['tid', 'name']);
        $query->condition('vid', $vid);
        $query->orderBy('name', 'ASC');
        //For the pagination we need to extend the pagerselectextender and
        //limit in the query
        $pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(3);
        $results = $pager->execute()->fetchAll();
        
        return $results;
    }

}

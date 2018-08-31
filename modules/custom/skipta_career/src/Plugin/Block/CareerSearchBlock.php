<?php
namespace Drupal\skipta_career\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
####################################################################################################
# Another new concept to Drupal that we need to use for the block is Annotations.                  #
# In order for Drupal to find your block code, you need to implement a code comment                #
# in a specific way, called an Annotation. An Annotation provides basic details of                 #
# the block such as an id and admin label. The admin label will appear on the block listing page.  #
#####################################################################################################
/**
 * Provides a 'Career Search Form' block.
 *
 * @Block(
 *   id = "career_search_form_block",
 *   admin_label = @Translation("Career Search Form"),
 *   category = @Translation("Skipta Career")
 * )
 */
class CareerSearchBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\skipta_career\Form\CareerSearchForm');
    return $form;
  }
    
}
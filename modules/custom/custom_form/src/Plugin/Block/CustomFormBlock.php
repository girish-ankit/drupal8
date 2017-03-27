<?php
namespace Drupal\custom_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
####################################################################################################
# Another new concept to Drupal that we need to use for the block is Annotations.                  #
# In order for Drupal to find your block code, you need to implement a code comment                #
# in a specific way, called an Annotation. An Annotation provides basic details of                 #
# the block such as an id and admin label. The admin label will appear on the block listing page.  #
#####################################################################################################

/**
 * Provides a 'User login' block.
 *
 * @Block(
 *   id = "custom_form_block",
 *   admin_label = @Translation("Custom form block"),
 *   category = @Translation("Custom Form")
 * )
 */

class CustomFormBlock extends BlockBase{
	

	
	/**
	 * {@inheritdoc}
	 */
	public function build() {
		return array(
				'#markup' => $this->t($this->configuration['custom_block_content']),   );
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function defaultConfiguration() {
		return array(
				'custom_block_content' => 'Hello world',
		);
	}
	/**
	 * {@inheritdoc}
	 */
	public function blockForm($form, FormStateInterface $form_state) {
		$form['custom_block_content'] = array(
				'#type' => 'textarea',
				'#title' => $this->t('Hello message'),
				'#default_value' => $this->configuration['custom_block_content'],
		);
	
		return $form;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function blockSubmit($form, FormStateInterface $form_state) {
		$this->configuration['custom_block_content'] = $form_state->getValue('custom_block_content');
	}
}
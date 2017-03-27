<?php
namespace Drupal\custom_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;

####################################################################################################
# Another new concept to Drupal that we need to use for the block is Annotations.                  #
# In order for Drupal to find your block code, you need to implement a code comment                #
# in a specific way, called an Annotation. An Annotation provides basic details of                 #
# the block such as an id and admin label. The admin label will appear on the block listing page.  #
#####################################################################################################
/**
 * Provides a 'Custom Test Block' Block.
 *
 * @Block(
 *   id = "custom_test_block",
 *   admin_label = @Translation("Custom Test block"),
 *   category = @Translation("Custom Form")
 * )
 */

class CustomTestBlock extends  BlockBase{
	
	/**
	 * {@inheritdoc}
	 */
	public function build() {
		return array(
				'#markup' => $this->t('Jai Shri Ram!'),
		);
	}
	
}

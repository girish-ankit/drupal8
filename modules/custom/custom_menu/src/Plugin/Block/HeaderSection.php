<?php

namespace Drupal\custom_menu\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\image\Entity\ImageStyle;
use \Drupal\user\Entity\User;
use Drupal\block\Entity\Block;

####################################################################################################
# Another new concept to Drupal that we need to use for the block is Annotations.                  #
# In order for Drupal to find your block code, you need to implement a code comment                #
# in a specific way, called an Annotation. An Annotation provides basic details of                 #
# the block such as an id and admin label. The admin label will appear on the block listing page.  #
#####################################################################################################
/**
 * Provides a 'Header Section' Block.
 *
 * @Block(
 *   id = "custom_header_section_block",
 *   admin_label = @Translation("Custom Header Section Block"),
 *   category = @Translation("Skipta Custom")
 * )
 */

class HeaderSection extends BlockBase {

    /**
     * {@inheritdoc}
     */
    public function build() {
        return array(
            '#theme' => 'header_section_template',
            '#description' => 'This block is created to show post login header',
            '#title' => 'Post Login Header',
            '#header' => $this->getData(),
        );
    }

    private function getData() {

        $uid = \Drupal::currentUser()->id();
        $logoPath = $this->logoPath();

        if (!$uid) {
            $data = array(
                'data' => array('name' => 'Gest', 'uid' => 0, 'logoPath' => $logoPath),
            );
        } else {

            $userData = $this->getUserData($uid);
            $notificationData = $this->getNoticiationData($uid);
            // How to get block id
           // $ids = \Drupal::entityQuery('block')->execute();
           // print_r($ids); 
            $menu = $this->getBlock('bartik_main_menu');
            $search = $this->getBlock('bartik_search');

            $data = array(
                'data' => array('logoPath' => $logoPath, 'user' => $userData, 'notfications' => $notificationData, 'headerMenu' => $menu, 'sarchBox' => $search),
            );
        }

        return $data;
    }

    private function logoPath() {

        $logoPath = '/' . drupal_get_path('theme', 'bartik') . '/logo.svg';

        return $logoPath;
    }

    private function getNoticiationData($uid) {
        $notificatoinData = array();

        for ($i = 0; $i < 5; $i++) {
            //$notificatoinData[$i] = \Drupal::l(t('Notification Title' . $i), Url::fromUri('internal:/user/1'));
            $notificatoinData[$i] = array('title' => t('Notification Title ' . $i), 'link' => Url::fromUri('internal:/user/1'));
        }


        return $notificatoinData;
    }

    private function getUserData($uid) {

        $userData = array();

        $user = User::load($uid);
// Some default getters include.
        $uid = $user->get('uid')->value;
        $name = $user->get('name')->value;
        $email = $user->get('mail')->value;



// Get field data from that user.
//  $website = $user->get('field_website')->value;
//  $body = $user->get('body')->value;
// user related data array
        $userData = array('uid' => $uid, 'name' => $name, 'email' => $email);

        return $userData;
    }

    private function getBlock($id) {

        // Load Instance of custom block with variables
        $example_block = \Drupal::entityManager()->getStorage('block')->load($id);
        if (!empty($example_block)) {
            $example_block_content = \Drupal::entityManager()
                    ->getViewBuilder('block')
                    ->view($example_block);
            if ($example_block_content) {
                return $example_block_content;
            } else {
                return $name.' block content not found';
            }
        }else{
            return $name.' not found';
        }
    }

}

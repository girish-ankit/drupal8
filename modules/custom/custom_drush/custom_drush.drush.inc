<?php

/**
 * @file Contains the code to generate the custom drush commands.
 */

/**
 * Implements hook_drush_command().
 */
function custom_drush_drush_command() {
    $items = array();
    $items['say-hello'] = [
        'description' => 'Echo the name you type with Say hello command',
        'arguments' => [
            'name' => 'The name for saying hello',
        ],
        // 'drupal dependencies' => ['custom_drush_command'],
        'aliases' => ['say:hello'],
    ];

    $items['my-example-command'] = [
        'description' => 'This is my example command.',
        'aliases' => ['mec'],
        'arguments' => [
            'arg1' => 'My custom argument 1.',
            'arg2' => 'My custom argument 2.',
        ],
        'options' => [
            'opt1' => 'My custom option.',
        ],
        'examples' => [
            'drush mec' => 'Print my example command.',
            'drush mec myargument' => 'Print my example command with an argument "myargument".',
            'drush mec myargument --opt1=myoption' => 'Print my example command with an argument "myargument" and an option "myoption".',
        ],
    ];
    return $items;
}

/**
 * Call back function drush_custom_drush_command_say_hello()
 * The call back function name in the  following format
 *   drush_{module_name}_{item_id_for_command}()
 */
function drush_custom_drush_say_hello($name = '') {
    drush_print('Hello ' . $name . ' !');
}

/**
 * Drush command logic.
 * drush_[MODULE_NAME]_[COMMAND_NAME]().
 */
function drush_custom_drush_my_example_command($arg1 = 'N/A', $arg2 = 'N/A') {
    $opt1 = drush_get_option('opt1', 'N/A');
    $tokens = ['@arg1' => $arg1, '@opt1' => $opt1];
    drush_print(dt('My custom command. Argument: @arg1 Option: @opt1', $tokens));
}

<?php

/**
 * Module Slider Builder
 * 
 * @author 	kuzmany.biz
 * @copyright 	kuzmany.biz/prestashop
 * @license 	kuzmany.biz/prestashop
 * Reminder: You own a single production license. It would only be installed on one online store (or multistore)
 */
require_once(dirname(__FILE__) . '../../../../../../config/config.inc.php');
require_once(dirname(__FILE__) . '../../../../../../init.php');

if(Tools::getValue('action') == 'container_save'){
    $short_code = Tools::getValue('shortcode');
    $short_code = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $short_code);
    $short_code = preg_replace('/<link\b[^>]*>/is', "", $short_code);
    echo trim($short_code);
}


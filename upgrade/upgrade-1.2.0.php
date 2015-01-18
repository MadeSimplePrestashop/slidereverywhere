<?php

/**
 * Module Sliders Everywhere
 * 
 * @author 	kuzmany.biz
 * @copyright 	kuzmany.biz/prestashop
 * @license 	kuzmany.biz/prestashop
 * Reminder: You own a single production license. It would only be installed on one online store (or multistore)
 */
if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_1_2_0($module) {
    // Change url type from varchar to text to avoid url length issues
    Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'sliderseverywhere_slides` ADD COLUMN `builder` TEXT NOT NULL');
    return $module;
}

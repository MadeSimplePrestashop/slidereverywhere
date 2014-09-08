<?php
/**
 * Module Sliders Everywhere 
 * 
 * @author 	kuzmany.biz
 * @copyright 	kuzmany.biz/prestashop
 * @license 	kuzmany.biz/prestashop
 * Reminder: You own a single production license. It would only be installed on one online store (or multistore)
 */
$sql = array();
$sql[] = 'DROP TABLE `' . _DB_PREFIX_ . 'sliderseverywhere`';
$sql[] = 'DROP TABLE `' . _DB_PREFIX_ . 'sliderseverywhere_hook`';
$sql[] = 'DROP TABLE `' . _DB_PREFIX_ . 'sliderseverywhere_shop`';
$sql[] = 'DROP TABLE `' . _DB_PREFIX_ . 'sliderseverywhere_slides`';
$sql[] = 'DROP TABLE `' . _DB_PREFIX_ . 'sliderseverywhere_slides_lang`';
$sql[] = 'DROP TABLE `' . _DB_PREFIX_ . 'sliderseverywhere_slides_shop`';

foreach ($sql as $s) {
    if (!Db::getInstance()->Execute($s)) {
        return false;
    }
}
?>
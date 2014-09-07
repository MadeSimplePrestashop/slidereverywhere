<?php

$sql = array();
$sql[] = 'DROP TABLE `' . _DB_PREFIX_ . 'sliderseverywhere`';
$sql[] = 'DROP TABLE `' . _DB_PREFIX_ . 'sliderseverywhere_shop`';
$sql[] = 'DROP TABLE `' . _DB_PREFIX_ . 'sliderseverywheres_slides`';
$sql[] = 'DROP TABLE `' . _DB_PREFIX_ . 'sliderseverywheres_slides_lang`';
$sql[] = 'DROP TABLE `' . _DB_PREFIX_ . 'sliderseverywheres_slides_shop`';

foreach ($sql as $s) {
    if (!Db::getInstance()->Execute($s)) {
        return false;
    }
}
?>
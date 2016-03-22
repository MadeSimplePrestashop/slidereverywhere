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
$sql[] = '
CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'sliderseverywhere` (
  `id_sliderseverywhere` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(50),
  `options` TEXT,
  PRIMARY KEY (`id_sliderseverywhere`)
) ENGINE = ' . _MYSQL_ENGINE_ . '  ';

$sql[] = '
CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'sliderseverywhere_hook` (
  `id_sliderseverywhere` int(11) NOT NULL ,
  `hook` varchar(40),
  KEY (`id_sliderseverywhere`)
) ENGINE = ' . _MYSQL_ENGINE_ . '  ';


$sql[] = ''
    . 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'sliderseverywhere_shop` (
      `id_sliderseverywhere` int(10)  NOT NULL,
      `id_shop` int(3) unsigned NOT NULL,
      PRIMARY KEY (`id_sliderseverywhere`, `id_shop`)
    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;'
    . '';


$sql[] = '
CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'sliderseverywhere_slides` (
  `id_sliderseverywhere_slides` int(11) NOT NULL AUTO_INCREMENT,
  `id_sliderseverywhere` int(11) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `target` VARCHAR(10) NOT NULL,
  `builder` TEXT NOT NULL,
  `position` int(3) NOT NULL,
  `active` int(1) NOT NULL,
  PRIMARY KEY (`id_sliderseverywhere_slides`)
) ENGINE = ' . _MYSQL_ENGINE_ . '  ';

$sql[] = '
CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'sliderseverywhere_slides_lang` (
  `id_sliderseverywhere_slides` int(11),
  `id_lang` int(3) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `video` TEXT NOT NULL,
  PRIMARY KEY (`id_sliderseverywhere_slides`,id_lang)
) ENGINE = ' . _MYSQL_ENGINE_ . '  ';

$sql[] = ''
    . 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'sliderseverywhere_slides_shop` (
      `id_sliderseverywhere_slides` int(10)  NOT NULL,
      `id_shop` int(3) unsigned NOT NULL,
      PRIMARY KEY (`id_sliderseverywhere_slides`, `id_shop`)
    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;'
    . '';

foreach ($sql as $s) {
    if (!Db::getInstance()->Execute($s)) {
        return false;
    }
}

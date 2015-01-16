<?php

/**
 * Module Accessories for combinations 
 * 
 * @author 	kuzmany.biz
 * @copyright 	kuzmany.biz/prestashop
 * @license 	kuzmany.biz/prestashop
 * Reminder: You own a single production license. It would only be installed on one online store (or multistore)
 */
require_once(dirname(__FILE__) . '../../../config/config.inc.php');
require_once(dirname(__FILE__) . '../../../init.php');

$builder_uri = __PS_BASE_URI__ . 'modules/sliderseverywhere/views/js/';
$builder_dir = _PS_ROOT_DIR_ . '/modules/sliderseverywhere/views/js/';

if (!class_exists('ZipArchive')) {
    echo Tools::jsonEncode(array(
        'response' => 'false',
        'message' => ('So sorry. But we can not load ZipArchive module for extract builder extension. Please extract the zip file in the directory ' . $builder_uri)
    ));
    return;
}

if (is_dir($builder_dir . 'azexo_composer')) {
    echo Tools::jsonEncode(array(
        'response' => 'ok',
        'message' => ('Everything looks perfect. Press OK and your site will be refresh.')
    ));
    return;
} else {
    $zip = new ZipArchive;
    if ($zip->open($builder_dir . 'azexo_composer.zip') === TRUE) {
        //make all the folders
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $OnlyFileName = $zip->getNameIndex($i);
            $FullFileName = $zip->statIndex($i);
            if (substr($FullFileName['name'], -1, 1) == "/") {
                mkdir($builder_dir . $FullFileName['name']);
            }
        }
        //unzip into the folders
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $OnlyFileName = $zip->getNameIndex($i);
            $FullFileName = $zip->statIndex($i);

            if (!(substr($FullFileName['name'], -1, 1) == "/")) {
                $fileinfo = pathinfo($OnlyFileName);
                copy('zip://' . $builder_dir . 'azexo_composer.zip' . '#' . $OnlyFileName, $builder_dir . $FullFileName['name']);
            }
        }
        $zip->close();
        $message = ('We are done and everything looks perfect. Press OK and your site will be refresh.');
    } else {
        $message = ('So sorry. But we can not load ZipArchive php extension. Please extract the zip file in the directory ' . $builder_uri.'. For more info please read documentation or contact us. We will help you.');
    }
    echo Tools::jsonEncode(array(
        'response' => 'ok',
        'message' => $message
    ));
}
die();
?>
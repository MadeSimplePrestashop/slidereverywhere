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

function upgrade_module_1_3_0($module)
{
    $languages = Context::getContext()->controller->getLanguages();
    $langs = array();
    foreach ($languages as $lang) {
        $langs[$lang['id_lang']] = $module->displayName;
    }
    $parent_tab = $module->installAdminTab($langs, 'Admin' . $module->name, 0);

    $id = Tab::getIdFromClassName('AdminSliders');
    $tab = new Tab($id);
    $tab->id_parent = $parent_tab->id;
    $langs = array();
    foreach ($languages as $lang) {
        $langs[$lang['id_lang']] = 'Sliders';
    }
    $tab->name = $langs;
    $tab->update();

    $id = Tab::getIdFromClassName('AdminSlides');
    $tab = new Tab($id);
    $tab->id_parent = $parent_tab->id;
    $tab->update();
    
    return true;
}

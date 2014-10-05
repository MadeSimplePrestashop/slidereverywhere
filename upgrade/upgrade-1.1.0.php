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


function upgrade_module_1_1_0($module) {
    //remove hooks table, it wasn't necessary
    $sliders = Sliders::getAll();
    foreach ($sliders as $slider) {
        $hooks = Sliders::get_hooks_by_id($slider[Sliders::$definition['primary']]);
        $hooks_array = array();
        foreach ($hooks as $hook)
            array_push($hooks_array, $hook['hook']);
        $slider_object = new Sliders($slider[Sliders::$definition['primary']]);
        $options = Tools::jsonDecode($slider_object->options);
        $options->hooks = $hooks_array;
        $slider_object->options = Tools::jsonEncode($options);
        $slider_object->save();
    }
    return $module;
}

<?php

/**
 * Module Slider Everywhere 
 * 
 * @author 	kuzmany.biz
 * @copyright 	kuzmany.biz/prestashop
 * @license 	kuzmany.biz/prestashop
 * Reminder: You own a single production license. It would only be installed on one online store (or multistore)
 */
if (!defined('_PS_VERSION_'))
    exit;

require_once(dirname(__FILE__) . '/models/Sliders.php');
require_once(dirname(__FILE__) . '/models/Slides.php');

class sliderseverywhere extends Module {

    public function __construct() {
        $this->name = 'sliderseverywhere';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'kuzmany.biz/prestashop';
        $this->need_instance = 0;
        $this->module_key = 'f881e7e331cc4f1c314de7f70fe72cd4';

        parent::__construct();

        $this->displayName = $this->l('Sliders Everywhere');
        $this->description = $this->l('Make sliders easy and put it whereever you want.');

        //Shop::addTableAssociation(EOCEPayment::$definition['table'], array('type' => 'shop'));
        //Shop::addTableAssociation(EOCEShipping::$definition['table'], array('type' => 'shop'));
    }

    public function install() {

        if (!parent::install() || !$this->registerHook('displayHeader')
        )
            return false;

        include_once(dirname(__FILE__) . '/init/install_sql.php');

        // Sliders
        $this->context->controller->getLanguages();
        $lang_array = array();
        $id_parent = Tab::getIdFromClassName('AdminParentModules');
        foreach ($this->context->controller->_languages as $language) {
            $lang_array[(int) $language['id_lang']] = $this->displayName;
        }
        $tab = $this->installAdminTab($lang_array, 'AdminSliders', $id_parent);
        $id_parent = $tab->id;
        //slides
        $lang_array = array();
        foreach ($this->context->controller->_languages as $language) {
            $lang_array[(int) $language['id_lang']] = 'Slides';
        }
        $this->installAdminTab($lang_array, 'AdminSlides', $id_parent);
        return true;
    }

    public function uninstall() {
        if (!parent::uninstall() || !$this->unregisterHook('displayHeader')
        )
            return false;

        include_once(dirname(__FILE__) . '/init/uninstall_sql.php');

        $this->uninstallAdminTab('AdminSliders');
        $this->uninstallAdminTab('AdminSlides');

        return true;
    }

    public function getContent() {
        Tools::redirectAdmin('index.php?controller=AdminSliders&token=' . Tools::getAdminTokenLite('AdminSliders'));
    }

    // set new carrier id
    public function hookActionCarrierUpdate($params) {
        
    }

    private function installAdminTab($name, $className, $parent) {
        $tab = new Tab();
        $tab->name = $name;
        $tab->class_name = $className;
        $tab->id_parent = $parent;
        $tab->module = $this->name;
        $tab->add();
        return $tab;
    }

    private function uninstallAdminTab($className) {
        $tab = new Tab((int) Tab::getIdFromClassName($className));
        $tab->delete();
    }

    public function hookHeader($params) {
        $this->context->controller->addCSS($this->getPathUri() . 'views/css/jquery.bxslider.css');
        $this->context->controller->addJS($this->getPathUri() . 'views/js/jquery.fitvids.js');
        if (!isset($this->context->smarty->registered_plugins['function'][$this->name]))
            $this->context->smarty->registerPlugin('function', $this->name, array('sliders', $this->name));
        if (!isset($this->context->smarty->registered_plugins['modifier']['truefalse']))
            $this->context->smarty->registerPlugin('modifier', 'truefalse', array('sliders', 'truefalse'));
    }

}

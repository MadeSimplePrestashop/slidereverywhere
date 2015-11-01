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

require_once(dirname(__FILE__) . '/models/Sliders.php');
require_once(dirname(__FILE__) . '/models/Slides.php');

class sliderseverywhere extends Module {

    public $hooks = array('displayTop', 'displayHome', 'displayLeftColumn', 'displayLeftColumnProduct',
        'displayRightColumn', 'displayRightColumnProduct', 'displayFooter', 'displayFooterProduct',
        'displayTopColumn', 'displayHomeTabContent', 'displayProductTab', 'displayShoppingCartFooter', 'displayBanner');

    public function __construct() {
        $this->name = 'sliderseverywhere';
        $this->tab = 'front_office_features';
        $this->version = '1.2.3';
        $this->author = 'kuzmany.biz/prestashop';
        $this->need_instance = 0;
        $this->module_key = '120f5f4af81ccec25515a5eb91a8d263';
        parent::__construct();

        $this->displayName = $this->l('Sliders Everywhere');
        $this->description = $this->l('Make sliders easy and put it whereever you want.');
    }

    public function install() {

        if (!parent::install() || !$this->registerHook('displayHeader') || !$this->registerHook('displayBackOfficeHeader'))
            return false;

        foreach ($this->hooks as $hook)
            $this->registerHook($hook);

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

        if (function_exists('curl_init') == false)
            $this->warning = $this->l('To be able to use this module, please activate cURL (PHP extension).');

        return true;
    }

    public function uninstall() {
        if (!parent::uninstall() || !$this->unregisterHook('displayHeader') || !$this->unregisterHook('displayBackOfficeHeader')
        )
            return false;

        foreach ($this->hooks as $hook)
            $this->unregisterHook($hook);

        include_once(dirname(__FILE__) . '/init/uninstall_sql.php');

        $this->uninstallAdminTab('AdminSliders');
        $this->uninstallAdminTab('AdminSlides');

        return true;
    }

    public function getContent() {

        Tools::redirectAdmin('index.php?controller=AdminSliders&token=' . Tools::getAdminTokenLite('AdminSliders'));
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

    public function hookDisplayBackOfficeHeader($params) {
        if (Dispatcher::getInstance()->getController() == 'AdminSlides') {
            $this->context->controller->addJquery();
            $this->hookHeader($params);
        }
    }

    public function hookHeader($params) {
        $this->context->controller->addCSS($this->getPathUri() . 'css/jquery.bxslider.css');
        $this->context->controller->addJS($this->getPathUri() . 'js/jquery.fitvids.js');
        $this->context->controller->addJqueryPlugin(array('bxslider'));
        if (!isset($this->context->smarty->registered_plugins['function'][$this->name]))
            $this->context->smarty->registerPlugin('function', $this->name, array('Sliders', 'get_slider'));
        if (!isset($this->context->smarty->registered_plugins['modifier']['truefalse']))
            $this->context->smarty->registerPlugin('modifier', 'truefalse', array('sliders', 'truefalse'));
        if (Tools::getValue('live_edit_token') && Tools::getValue('live_edit_token') == $this->getLiveEditToken()){
            
        }else {
            $slides = Slides::getAll();
        }

        $html = '';
        if (Tools::getValue('se_live_edit_token') && Tools::getValue('se_live_edit_token') == Sliders::getLiveEditToken() && Tools::getIsset('id_employee')) {
            $this->context->controller->addCSS($this->getPathUri() . '/css/inspector.css', 'all');
            //$this->context->controller->addJS($this->getPathUri() . '/js/firebug/build/firebug-lite.js');
            $html = '<script type="text/javascript" src="' . $this->_path . 'js/firebug/build/firebug-lite.js#startOpened"></script>';
            $this->context->controller->addJS($this->getPathUri() . '/js/inspector.js');
        }

        return $html;
    }

    /**
     * Function with cache mechanism, prevent to many sql request
     * @param type $hook
     * @return type
     */
    private function find_ids_from_hooks($hook) {
        $sliders = Cache::retrieve(__CLASS__ . __FUNCTION__);
        if ($sliders == -1)
            return array();
        if (!$sliders) {
            $sliders = Sliders::getAll();
            if ($sliders)
                Cache::store(__CLASS__ . __FUNCTION__, $sliders);
            else
                Cache::store(__CLASS__ . __FUNCTION__, -1);
        }

        $ids = array();
        foreach ($sliders as $slider) {
            $options = Tools::jsonDecode($slider['options']);
            if (!is_array($options->hooks))
                continue;
            if (!in_array($hook, $options->hooks))
                continue;
            $ids[] = $slider[Sliders::$definition['primary']];
        }
        return $ids;
    }

    /**
     * Universal function for loading sliders in hook
     * @param type $hook_func
     * @return type
     */
    private function load_hook_sliders($hook_func) {
        $hook = lcfirst(str_replace('hook', '', $hook_func));
        $ids = $this->find_ids_from_hooks($hook);
        if (!$ids)
            return;
        $html = '';
        foreach ($ids as $slider)
            $html .= Sliders::get_slider(array('id' => $slider));

        return $html;
    }

    // hooks
    public function hookDisplayTop($params) {
        return $this->load_hook_sliders(__FUNCTION__);
    }

    public function hookDisplayHome($params) {
        return $this->load_hook_sliders(__FUNCTION__);
    }

    public function hookDisplayLeftColumn($params) {
        return $this->load_hook_sliders(__FUNCTION__);
    }

    public function hookDisplayLeftColumnProduct($params) {
        return $this->load_hook_sliders(__FUNCTION__);
    }

    public function hookDisplayRightColumn($params) {
        return $this->load_hook_sliders(__FUNCTION__);
    }

    public function hookDisplayRightColumnProduct($params) {
        return $this->load_hook_sliders(__FUNCTION__);
    }

    public function hookDisplayFooter($params) {

        return $this->load_hook_sliders(__FUNCTION__);
    }

    public function hookDisplayFooterProduct($params) {
        return $this->load_hook_sliders(__FUNCTION__);
    }

    public function hookDisplayTopColumn($params) {
        return $this->load_hook_sliders(__FUNCTION__);
    }

    public function hookDisplayHomeTabContent($params) {
        return $this->load_hook_sliders(__FUNCTION__);
    }

    public function hookDisplayProductTab($params) {
        return $this->load_hook_sliders(__FUNCTION__);
    }

    public function hookDisplayShoppingCartFooter($params) {
        return $this->load_hook_sliders(__FUNCTION__);
    }

    public function hookDisplayBanner($params) {
        return $this->load_hook_sliders(__FUNCTION__);
    }
}

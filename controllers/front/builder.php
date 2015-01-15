<?php

/**
 * Module Sliders Everywhere
 * 
 * @author 	kuzmany.biz
 * @copyright 	kuzmany.biz/prestashop
 * @license 	kuzmany.biz/prestashop
 * Reminder: You own a single production license. It would only be installed on one online store (or multistore)
 */
require_once(_PS_MODULE_DIR_ . 'sliderseverywhere/models/Slides.php');

class sliderseverywherebuilderModuleFrontController extends ModuleFrontController {

    /**
     * Assign template vars related to page content
     * @see FrontController::initContent()
     */
    public function initContent() {
        parent::initContent();
        if (Tools::getValue('live_edit_token') && Tools::getValue('live_edit_token') == $this->module->getLiveEditToken()) {
//            $id_slide = Tools::getValue(Slides::$definition['primary']);
//            if ($id_slide) {
//                $slide = new Slides($id_slide);
//                $this->context->smarty->assign(array(
//                    'builder' => $slide->builder
//                ));
//            }

            $this->setTemplate('builder.tpl');
        }
    }

    public function setMedia() {
        parent::setMedia();
        if (Tools::getValue('live_edit_token') && Tools::getValue('live_edit_token') == $this->module->getLiveEditToken()) {
            
            $this->addCSS(dirname(__FILE__) . '/../../views/js/bootstrap/bootstrap.min.css');
            $this->addCSS(dirname(__FILE__) . '/../../views/js/azexo_composer/azexo_composer.css');
            $this->addCSS(dirname(__FILE__) . '/../../views/js/azexo_composer/azexo_composer_add.css');
            
            $this->addJS(dirname(__FILE__) . '/../../views/js/azexo_composer/underscore-min.js');
            $this->addJS(dirname(__FILE__) . '/../../views/js/azexo_composer/js/smoothscroll.js');
            $this->addJS(dirname(__FILE__) . '/../../views/js/azexo_composer/jquery-waypoints/waypoints.min.js');
            
            $this->addJS(dirname(__FILE__) . '/../../views/js/azexo_composer/init_admin.js');
            
            
            $this->addJS(dirname(__FILE__) . '/../../views/js/jquery-ui.min.js');
            $this->addJS(dirname(__FILE__) . '/../../views/js/azexo_composer/azexo_param_types.js');
            $this->addJS(dirname(__FILE__) . '/../../views/js/azexo_composer/azexo_elements.js');
            $this->addJS(dirname(__FILE__) . '/../../views/js/azexo_composer/azexo_composer.js');
        }
    }

}

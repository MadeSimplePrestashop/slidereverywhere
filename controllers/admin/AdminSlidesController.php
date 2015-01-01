<?php

/**
 * Module Sliders Everywhere
 * 
 * @author 	kuzmany.biz
 * @copyright 	kuzmany.biz/prestashop
 * @license 	kuzmany.biz/prestashop
 * Reminder: You own a single production license. It would only be installed on one online store (or multistore)
 */
require_once(_PS_MODULE_DIR_ . 'sliderseverywhere/models/Sliders.php');
require_once(_PS_MODULE_DIR_ . 'sliderseverywhere/models/Slides.php');

class AdminSlidesController extends ModuleAdminController {

    protected $position_identifier = 'id_sliderseverywhere_slides';
    protected static $parent_definition;

    public function __construct() {

        self::$parent_definition = Sliders::$definition;

        $this->bootstrap = true;

        $this->table = Slides::$definition['table'];
        $this->className = 'Slides';

        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?')
            )
        );

        $this->lang = true;
        parent::__construct();
    }

    private function get_image_path($id_dir) {
        return _PS_MODULE_DIR_ . $this->module->name . '/img/' . $id_dir . '/';
    }

    public function initContent() {
        parent::initContent();
    }

    public function postProcess() {
        $obj = $this->loadObject(true);
//reload object is bulk action
        if (Tools::getIsset('submitFilter' . $this->table) && Tools::getValue('submitFilter' . $this->table) == 0) {
            $checked = Tools::getValue($this->table . 'Box');
            if (isset($checked[0]))
                $obj = new Slides($checked[0]);
        }
        $par = self::$parent_definition['primary'];

        parent::postProcess();
        if (Tools::getIsset('submitFilter' . $this->table)) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminSlides') . '&' . self::$parent_definition['primary'] . '=' . $obj->$par);
        } elseif (Tools::getIsset('status' . $this->table))
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminSlides') . '&' . self::$parent_definition['primary'] . '=' . $obj->$par);
        elseif (Tools::getIsset('delete' . $this->table))
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminSlides') . '&' . self::$parent_definition['primary'] . '=' . $obj->$par);
        elseif (Tools::getIsset('submitStay'))
            Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminSlides') . '&' . Slides::$definition['primary'] . '=' . $this->object->id . '&update' . $this->table);
        elseif (Tools::isSubmit('submitAdd' . $this->table))
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminSlides') . '&' . self::$parent_definition['primary'] . '=' . Tools::getValue(self::$parent_definition['primary']));
    }

    public function renderForm() {

        $par = self::$parent_definition['primary'];

        if (!$obj = $this->loadObject(true))
            return;
        if ($obj->image) {
            $dir = _PS_MODULE_DIR_ . $this->module->name . '/img/' . $obj->$par . '/';
            $image = $dir . $obj->image;
        } else
            $image = '';

        $params = array('live_edit_token' => $this->module->getLiveEditToken(), 'id_employee' => $this->context->employee->id);
        if ($obj->id)
            $params[Slides::$definition['primary']] = $obj->id;
        $builder_url = $this->context->link->getModuleLink('sliderseverywhere', 'builder', $params);

        $builder_value = '<input type="hidden" name="builder" id="builder" /><a href="' . $builder_url . '"  target="_blank"><button type="button"  class="btn btn-default">' . $this->l('Open builder in new window') . '</button></a>';
        if (isset($obj) && isset($obj->builder) && empty($obj->builder) == false)
            $builder_value .= '<br /><br /> <small>' . $this->l('Preview (not with full functionality, just preview)') . '</small> <br /><div id="az-preview" class="az-container">' . urldecode($obj->builder) . '</div>';

        $this->fields_value = array('builder' => $builder_value);
        $this->fields_form = array(
            'legend' => array(
                'tinymce' => true,
                'title' => $this->l('Slide'),
                'icon' => 'icon-cogs'
            ),
            'tabs' => array(
                'settings' => $this->l('Settings'),
                'image' => $this->l('Image'),
                'video' => $this->l('Video'),
                'buildertab' => $this->l('Builder')
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => self::$parent_definition['primary']
                ),
                array(
                    'tab' => 'image',
                    'type' => 'file',
                    'label' => $this->l('Image'),
                    'name' => 'image',
                    'display_image' => true,
                    'image' => $image ? ImageManager::thumbnail($image, 'thumb_detail_' . $obj->image, 200) : ''
                ),
                array(
                    'tab' => 'video',
                    'type' => 'textarea',
                    'lang' => true,
                    'label' => $this->l('Embeded video'),
                    'desc' => $this->l('Instead of image, you can add embeded video supported YouTube and Vimeo.'),
                    'name' => 'video',
                ),
                array(
                    'tab' => 'image',
                    'type' => 'text',
                    'label' => $this->l('Caption'),
                    'hint' => $this->l('Short description for slide'),
                    'lang' => true,
                    'name' => 'caption'
                ),
                array(
                    'tab' => 'image',
                    'type' => 'text',
                    'label' => $this->l('Url'),
                    'hint' => $this->l('Associate    url (optional)'),
                    'lang' => true,
                    'name' => 'url'
                ),
                'target' => array(
                    'tab' => 'image',
                    'type' => 'select',
                    'label' => $this->l('Target'),
                    'name' => 'target',
                    'hint' => $this->l('Target open window for url'),
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => '',
                                'name' => $this->l('None')
                            ),
                            array(
                                'id' => '_blank',
                                'name' => $this->l('_blank')
                            ),
                            array(
                                'id' => '_parent',
                                'name' => $this->l('_parent')
                            ),
                            array(
                                'id' => '_self',
                                'name' => $this->l('_self')
                            ),
                            array(
                                'id' => '_top',
                                'name' => $this->l('_top')
                            )
                        ),
                        'id' => 'id',
                        'name' => 'name',
                    )
                ),
                array(
                    'tab' => 'buildertab',
                    'type' => 'free',
                    'name' => 'builder',
                ),
                array(
                    'tab' => 'settings',
                    'type' => 'switch',
                    'label' => $this->l('Active'),
                    'name' => 'active',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                    'default_value' => 1
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right',
                'name' => 'submit',
            )
        );

        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = array(
                'tab' => 'settings',
                'type' => 'shop',
                'label' => $this->l('Shop association:'),
                'name' => 'checkBoxShopAsso',
            );
        }


        $this->page_header_toolbar_btn['save'] = array(
            'href' => 'javascript:$("#' . $this->table . '_form button:submit").click();',
            'desc' => $this->l('Save')
        );
        $this->page_header_toolbar_btn['save-and-stay'] = array(
            'short' => 'SaveAndStay',
            'href' => 'javascript:$("#' . $this->table . '_form").attr("action", $("#' . $this->table . '_form").attr("action")+"&submitStay");$("#' . $this->table . '_form button:submit").click();',
            'desc' => $this->l('Save and stay'),
            'force_desc' => true,
        );
        $this->page_header_toolbar_btn['delete'] = array(
            'href' => $this->context->link->getAdminLink('AdminSlides', true) . '&' . self::$parent_definition['primary'] . '=' . ((isset($obj->id) && $obj->id) ? $obj->$par : Tools::getValue(self::$parent_definition['primary'])),
            'icon' => 'process-icon-cancel',
            'desc' => $this->l('Back to slides list'),
        );

//back button
        $this->content.= '<script>
         $(document).ready(function(){
            $(\'.panel-footer a\').click(function(e){e.preventDefault(); window.history.back();})
        })
                </script>';

        return parent::renderForm();
    }

    public function renderList() {

        if (!Tools::getValue(self::$parent_definition['primary'])) {
            $this->page_header_toolbar_btn['save'] = array(
                'href' => $this->context->link->getAdminLink('AdminSliders', true),
                'icon' => 'process-icon-cancel',
                'desc' => $this->l('Back to sliders list'),
            );
            $this->errors[] = Tools::displayError('Can\'t identify slider. Please <a href="' . $this->context->link->getAdminLink('AdminSliders', true) . '">go back</a> to sliders.', false);
            return parent::renderList();
        }

        $this->fields_list = array(
            'image' => array(
                'title' => $this->l('Image'),
                'type' => 'text',
                'orderby' => false,
                'search' => false,
                'callback' => 'getImage'
            ),
            'caption' => array(
                'title' => $this->l('Caption'),
                'type' => 'text',
                'orderby' => false,
                'search' => false
            ),
            'position' => array(
                'title' => $this->l('Position'),
                'width' => 40,
                'position' => 'position',
                'orderby' => false,
                'search' => false
            ),
            'active' => array(
                'title' => $this->l('Active'),
                'active' => 'status',
                'type' => 'bool',
                'orderby' => false,
                'search' => false
            )
        );

        $this->_join = 'LEFT JOIN ' . _DB_PREFIX_ . self::$parent_definition['table'] . ' AS c ON a.`' . self::$parent_definition['primary'] . '` = c.`' . self::$parent_definition['primary'] . '`';
        $this->_where = 'AND a.`' . self::$parent_definition['primary'] . '` = ' . (int) Tools::getValue(self::$parent_definition['primary']);
        $this->_orderBy = 'position';
        $this->page_header_toolbar_btn['new'] = array(
            'href' => $this->context->link->getAdminLink('AdminSlides', true) . '&add' . Slides::$definition['table'] . '&' . self::$parent_definition['primary'] . '=' . Tools::getValue(self::$parent_definition['primary']),
            'desc' => $this->l('Add new slide'),
            'icon' => 'process-icon-new'
        );
        $this->page_header_toolbar_btn['newField'] = array(
            'href' => $this->context->link->getAdminLink('AdminSliders', true) . '&update' . self::$parent_definition['table'] . '&' . self::$parent_definition['primary'] . '=' . Tools::getValue(self::$parent_definition['primary']),
            'icon' => 'process-icon-edit',
            'desc' => $this->l('Edit slider'),
        );
        $this->page_header_toolbar_btn['edit'] = array(
            'href' => 'javascript:$("#previewslider").toggle(); slidereverywhere.reloadSlider();',
            'icon' => 'process-icon-preview',
            'desc' => $this->l('Preview toggle'),
        );
        $this->page_header_toolbar_btn['save'] = array(
            'href' => $this->context->link->getAdminLink('AdminSliders', true),
            'icon' => 'process-icon-back',
            'desc' => $this->l('Back to sliders list'),
        );

        $this->toolbar_btn['new'] = array(
            'href' => $this->context->link->getAdminLink('AdminSlides', true) . '&add' . Slides::$definition['table'] . '&' . self::$parent_definition['primary'] . '=' . Tools::getValue(self::$parent_definition['primary']),
            'desc' => $this->l('Add slide')
        );

        $this->content .= '<div id="previewslider" style="display:none">' . Sliders::get_slider(array('id' => Tools::getValue(self::$parent_definition['primary']))) . '</div>';
        $this->content.= '<script>
         $(document).ready(function(){
         if(location.hash == "#preview")
            $("#previewslider").show(); slidereverywhere.reloadSlider();
        })
                </script>';
// set new title
        $slider = new Sliders(Tools::getValue(self::$parent_definition['primary']));
        $this->tpl_list_vars['title'] = $this->l('Slides of ') . $slider->alias;
        return parent::renderList();
    }

    public function ajaxProcessUpdatePositions() {
        if ($this->tabAccess['edit'] === '1') {
            $id_to_move = (int) Tools::getValue('id');
            $way = (int) Tools::getValue('way');
            $object = new Slides($id_to_move);
            $positions = Tools::getValue(Slides::$definition['table']);

            if (is_array($positions)) {
                foreach ($positions as $key => $value) {
                    $pos = explode('_', $value);
                    if ((isset($pos [1]) && isset($pos[2])) && ($pos [2] == $id_to_move)) {
                        $position = $key;
                        break;
                    }
                }
            }
            if (Validate::isLoadedObject($object)) {
                if (isset($position) && $object->updatePosition($way, $position))
                    die(true);
                else
                    die('{"hasError" : true, "errors" : "Can not update categories position"}' . $position);
            } else
                die('{"hasError" : true, "errors" : "This category can not be loaded"}');
        }
    }

//render image at renderList
    public function getImage($echo, $row) {
        if (isset($row['image']) && $row['image'])
            return ImageManager::thumbnail($this->get_image_path($row[self::$parent_definition['primary']]) . $echo, 'thumb_' . $echo, 50);
        elseif (isset($row['video']) && $row['video'])
            return $this->l('video');
    }

}

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

class AdminSlidesController extends ModuleAdminController {

    protected $position_identifier = 'id_sliderseverywhere_slides';

    public function __construct() {
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
        parent::postProcess();
    }

    public function renderForm() {

        if (!$obj = $this->loadObject(true))
            return;
        if ($obj->image) {
            $par = Sliders::$definition['primary'];
            $dir = _PS_MODULE_DIR_ . $this->module->name . '/img/' . $obj->$par . '/';
            $image = $dir . $obj->image;
        } else
            $image = '';
        $this->fields_form = array(
            'legend' => array(
                'tinymce' => true,
                'title' => $this->l('Add new slide'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => Sliders::$definition['primary']
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Image'),
                    'name' => 'image',
                    'display_image' => true,
                    'image' => $image ? ImageManager::thumbnail($image, 'thumb_detail_' . $obj->image, 200) : ''
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Video'),
                    'desc' => $this->l('Instead of image, you can add video'),
                    'lang' => true,
                    'name' => 'video'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Caption'),
                    'desc' => $this->l('Short description for slide'),
                    'lang' => true,
                    'name' => 'caption'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Url'),
                    'desc' => $this->l('Associate    url (optional)'),
                    'lang' => true,
                    'name' => 'url'
                ),
                'target' => array(
                    'type' => 'select',
                    'label' => $this->l('Target'),
                    'name' => 'target',
                    'desc' => $this->l('Target open window for url'),
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => '',
                                'name' => $this->l('None')
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
                    ),
                    'default_value' => isset($options->target) ? $options->target : ''
                ),
                array(
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
                'type' => 'shop',
                'label' => $this->l('Shop association:'),
                'name' => 'checkBoxShopAsso',
            );
        }

        //back button
        $this->content.= '<script>
         $(document).ready(function(){
            $(\'.panel-footer a\').click(function(e){e.preventDefault(); window.history.back();})
        })
                </script>';

        return parent::renderForm();
    }

    public function renderList() {
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
        $this->_join = 'LEFT JOIN ' . _DB_PREFIX_ . Sliders::$definition['table'] . ' AS c ON a.`' . Sliders::$definition['primary'] . '` = c.`' . Sliders::$definition['primary'] . '`';
        $this->_where = 'AND a.`' . Sliders::$definition['primary'] . '` = ' . (int) Tools::getValue(Sliders::$definition['primary']);
        $this->_orderBy = 'position';

        $this->toolbar_btn['back'] = array(
            'href' => $this->context->link->getAdminLink('AdminSliders', true),
            'desc' => $this->l('Back to the sliders')
        );
        $this->toolbar_btn['new'] = array(
            'href' => $this->context->link->getAdminLink('AdminSlides', true) . '&add' . Slides::$definition['table'] . '&' . Sliders::$definition['primary'] . '=' . Tools::getValue(Sliders::$definition['primary']),
            'desc' => $this->l('Add slide')
        );

        $this->page_header_toolbar_btn['new'] = array(
            'href' => self::$currentIndex . '&add' . $this->table . '&token=' . $this->token,
            'desc' => $this->l('Add new slide'),
            'icon' => 'process-icon-new'
        );

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
                    if ((isset($pos[1]) && isset($pos[2])) && ($pos[2] == $id_to_move)) {
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
            return ImageManager::thumbnail($this->get_image_path($row[Sliders::$definition['primary']]) . $row['image'], 'thumb_' . $row['image'], 50);
        elseif (isset($row['video']) && $row['video'])
            return $row['video'];
    }

}

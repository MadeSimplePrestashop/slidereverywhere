<?php

/**
 * Module Sliders Everywhere
 * 
 * @author 	kuzmany.biz
 * @copyright 	kuzmany.biz/prestashop
 * @license 	kuzmany.biz/prestashop
 * Reminder: You own a single production license. It would only be installed on one online store (or multistore)
 */
class Sliders extends ObjectModel {

    public $id_sliderseverywhere;
    public $alias;
    public $options;

    public function __construct($id = null, $id_lang = null, $id_shop = null) {
        self::_init();
        parent::__construct($id, $id_lang, $id_shop);
    }

    private static function _init() {
        if (Shop::isFeatureActive())
            Shop::addTableAssociation(self::$definition['table'], array('type' => 'shop'));
    }

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'sliderseverywhere',
        'primary' => 'id_sliderseverywhere',
        'fields' => array(
            'alias' => array('type' => self::TYPE_STRING, 'required' => true),
            'options' => array('type' => self::TYPE_STRING, 'validate' => 'isString')
        )
    );

    public static function getAll($parms = array()) {
        self::_init();
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from(self::$definition['table'], 'c');
        if (Shop::isFeatureActive())
            $sql->innerJoin(self::$definition['table'] . '_shop', 's', 'c.' . self::$definition['primary'] . ' = s.' . self::$definition['primary'] . ' AND s.id_shop = ' . (int) Context::getContext()->shop->id);
        if (isset($parms['hook']))
            $sql->leftJoin(self::$definition['table'] . '_hook', 'h', 'c.' . self::$definition['primary'] . ' = h.' . self::$definition['primary'] . ' AND h.hook = ' . (string) $parms['hook']);
        if (empty($parms) == false)
            foreach ($parms as $k => $p)
                $sql->where('' . $k . ' =\'' . $p . '\'');
        return Db::getInstance()->executeS($sql);
    }

    private function transform_options() {
        $parms = $_POST;
        unset($parms[self::$definition['primary']]);
        foreach (self::$definition['fields'] as $key => $field)
            unset($parms[$key]);
        return Tools::jsonEncode($parms);
    }

    public function update($null_values = false) {

//transform alias
        $this->alias = Tools::strtolower(str_replace(' ', '', Tools::replaceAccentedChars($this->alias)));

        $this->options = $this->transform_options();
        parent::update($null_values);

//reload images
        $parms = array();
        $parms[self::$definition['primary']] = $this->id;
        $slides = Slides::getAll($parms);
        $source_path = Slides::get_image_path($this->id);
        if ($slides) {
            foreach ($slides as $key => $slide) {
                echo ImageManager::thumbnail($source_path . $slide['image'], '/pager_' . $slide['image'], Tools::getValue('thumbnailWidth'), 'jpg', true, true);
            }
        }
        $this->add_hooks($this->id, Tools::getValue('hooks'));

        if (Tools::getIsset('submitPreview'))
            Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminSlides') . '&' . self::$definition['primary'] . '=' . $this->id . '#preview');
        else
            Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminSliders'));
    }

    public function add($autodate = true, $null_values = false) {
        
        $this->options = $this->transform_options();
        parent::add($autodate, $null_values);

        $this->add_hooks($this->id, Tools::getValue('hooks'));

        if (Tools::getIsset('submitPreview'))
            Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminSlides') . '&' . self::$definition['primary'] . '=' . $this->id . '#preview');
        else
            Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminSliders'));
    }

    public static function findIdByAlias($alias) {
        $sql = 'SELECT ' . self::$definition['primary'] . '
			FROM `' . _DB_PREFIX_ . self::$definition['table'] . '`
			WHERE `alias` = \'' . (string) $alias . '\'';
        return (Db::getInstance()->getValue($sql));
    }

    public static function load_slider($id) {

// find children
        $parms = array();
        $parms[self::$definition['primary']] = $id;
        $parms['active'] = 1;
        $slides = Slides::getAll($parms);
        if (empty($slides))
            return;

//echo ImageManager::resize($source_path . $slide['image'], _PS_TMP_IMG_DIR_. '/web_' . $slide['image'],300,200);
        $slider = new Sliders($id, null, Context::getContext()->shop->id);
        $slider->options = Tools::jsonDecode($slider->options);
        foreach ($slides as $key => $slide) {
            if ($slide['image']) {
                $source_path = Slides::get_image_path($id);
                $image_temp = $image = $slide['image'];
            } else {
                $source_path = Slides::get_image_path();
                $image = 'empty.jpg';
                $image_temp = $slider->options->thumbnailWidth . '_' . $image;
            }
            $slides[$key]['image_helper']['thumb'] = ImageManager::thumbnail($source_path . $image, '/pager_' . $image_temp, $slider->options->thumbnailWidth);
            $slides[$key]['image_helper']['dir'] = _MODULE_DIR_ . self::$definition['table'] . '/img/' . $id . '/';
            list($w, $h) = @getimagesize($source_path . $image);
            $slides[$key]['image_helper']['width'] = $w;
            $slides[$key]['image_helper']['height'] = $h;

            //if video 
            if ($slide['video']) {
                $slider->options->video = true;
                $slider->options->useCSS = false;
            }
        }

        Context::getContext()->smarty->smarty->assign(array(
            'slider' => $slider,
            'slides' => $slides
        ));
        return Context::getContext()->smarty->fetch(
                        dirname(__FILE__) . '/../views/templates/hook/slider.tpl');
    }

    public function delete() {
        parent::delete();
        $this->delete_hooks($this->id);
        $slides = Slides::getAll(array(self::$definition['primary'] => $this->id));
        if ($slides) {
            foreach ($slides as $slide) {
                $slide_obj = new Slides($slide[Slides::$definition['primary']]);
                $slide_obj->delete();
            }
        }
        Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminSliders'));
    }

// smarty
    public static function get_slider($params) {

        $id = '';
        if (isset($params['alias'])) {
            $alias = $params['alias'];
            $id = self::findIdByAlias($alias);
        } elseif (isset($params['id']))
            $id = $params['id'];

        if (empty($id))
            return;

        $result = self::load_slider($id);
        if (isset($params['assign'])) {
            Context::getContext()->smarty->assign(trim($params['assign']), $result);
            return;
        }
        return $result;
    }

    public static function truefalse($truefalse, $assign = null) {

        if ($truefalse)
            $result = 'true';
        else
            $result = 'false';

        if ($assign != null) {
            Context::getContext()->smarty->assign(trim($assign), $result);
            return;
        }
        return $result;
    }

    /*
     * Hooks
     */

    public static function get_ids_by_hook($hook) {
        $sql = 'SELECT ' . self::$definition['primary'] . '
			FROM `' . _DB_PREFIX_ . self::$definition['table'] . '_hook`
			WHERE `hook` = \'' . $hook . '\'';
        return (Db::getInstance()->executeS($sql));
    }

    public static function get_hooks_by_id($id) {
        $sql = 'SELECT hook
			FROM `' . _DB_PREFIX_ . self::$definition['table'] . '_hook`
			WHERE `' . self::$definition['primary'] . '` = ' . $id;

        return (Db::getInstance()->executeS($sql));
    }

    private function delete_hooks($id) {
        Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . self::$definition['table'] . '_hook` WHERE ' . self::$definition['primary'] . '=' . $id);
    }

    private function add_hooks($id, $hooks) {
        $this->delete_hooks($id);
        if ($hooks) {
            foreach ($hooks as $hook) {
                $sql = 'INSERT INTO `' . _DB_PREFIX_ . self::$definition['table'] . '_hook`
                    VALUES("' . (int) $id . '","' . (string) $hook . '")';
                Db::getInstance()->execute($sql);
            }
        }
    }

}

?>
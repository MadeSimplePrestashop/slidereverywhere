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
        $id_lang = Context::getContext()->language->id;
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from(self::$definition['table'], 'c');
        if (Shop::isFeatureActive())
            $sql->innerJoin(self::$definition['table'] . '_shop', 's', 'c.' . self::$definition['primary'] . ' = s.' . self::$definition['primary'] . ' AND s.id_shop = ' . (int) Context::getContext()->shop->id);
        if (empty($parms) == false)
            foreach ($parms as $k => $p)
                $sql->where('' . $k . ' =\'' . $p . '\'');
        return Db::getInstance()->executeS($sql);
    }

    public function update($null_values = false) {
        $this->alias = strtolower(str_replace(' ', '', Tools::replaceAccentedChars($this->alias)));
        $parms = $_POST;
        unset($parms[self::$definition['primary']]);
        foreach (self::$definition['fields'] as $key => $field)
            unset($parms[$key]);
        $this->options = Tools::jsonEncode($parms);
        parent::update($null_values);
        Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminSliders'));
    }

    public function add($autodate = true, $null_values = false) {
        parent::add($autodate, $null_values);
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
        $source_path = Slides::get_image_path($id);
        foreach ($slides as $key => $slide) {
            if ($slide['image']) {
                $slides[$key]['image_helper']['thumb'] = ImageManager::thumbnail($source_path . $slide['image'], '/pager_' . $slide['image'], $slider->options->thumbnailWidth);
                $slides[$key]['image_helper']['dir'] = _MODULE_DIR_ . self::$definition['table'] . '/img/' . $id . '/';
                list($w, $h, $t, $a) = @getimagesize($source_path . $slide['image']);
                $slides[$key]['image_helper']['width'] = $w;
                $slides[$key]['image_helper']['height'] = $h;
            }elseif($slide['video']){
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

    // smarty
    public static function sliderseverywhere($params) {

        if (!isset($params['alias']))
            return;
        $alias = $params['alias'];

        $id = self::findIdByAlias($alias);
        if (empty($id))
            return;
        $result = self::load_slider($id);
        if (isset($params['assign'])) {
            $smarty->assign(trim($params['assign']), $result);
            return;
        }
        return $result;
    }

    public static function truefalse($truefalse) {

        if ($truefalse)
            $result = 'true';
        else
            $result = 'false';

        if (isset($params['assign'])) {
            $smarty->assign(trim($params['assign']), $result);
            return;
        }
        return $result;
    }

}

?>
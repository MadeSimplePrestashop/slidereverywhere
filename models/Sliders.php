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
        if (empty($parms) == false)
            foreach ($parms as $k => $p)
                $sql->where('' . $k . ' =\'' . $p . '\'');
        return Db::getInstance()->executeS($sql);
    }

    private function transform_options() {
        if (!Tools::getIsset('submitUpdate' . self::$definition['table']) && !Tools::getIsset('submitAdd' . self::$definition['table']))
            return false;
        $parms = array();
        foreach (self::get_option_fields() as $option)
            $parms[$option] = Tools::getValue($option);
        return Tools::jsonEncode($parms);
    }

    public function update($null_values = false) {
        $this->alias = Tools::strtolower(str_replace(' ', '', Tools::replaceAccentedChars($this->alias)));
        $options = $this->transform_options();
        if ($options != false)
            $this->options = $options;
        parent::update($null_values);

//reload images
        $parms = array();
        $parms[self::$definition['primary']] = $this->id;
        $slides = Slides::getAll($parms);
        $source_path = Slides::get_image_path($this->id);
        if ($slides) {
            foreach ($slides as $slide) {
                ImageManager::thumbnail($source_path . $slide['image'], '/pager_' . $slide['image'], Tools::getValue('thumbnailWidth'), 'jpg', true, true);
            }
        }
    }

    public function add($autodate = true, $null_values = false) {
        $options = $this->transform_options();
        if ($options != false)
            $this->options = $options;
        parent::add($autodate, $null_values);
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

        //echo ImageManagr::resize($source_path . $slide['image'], _PS_TMP_IMG_DIR_. '/web_' . $slide['image'],300,200);
        $slider = new Sliders($id, null, Context::getContext()->shop->id);
        $slider->options = Tools::jsonDecode($slider->options);

        $view = array();
        if (Dispatcher::getInstance()->getController() != 'AdminSlides') {
            (isset($slider->options->categories) && empty($slider->options->categories) == false ? array_push($view, 'category') : '');
            (isset($slider->options->cms) && empty($slider->options->cms) == false ? array_push($view, 'cms') : '');
        }

        if (empty($view) == false) {
            if (!in_array(Dispatcher::getInstance()->getController(), $view))
                return;

            if (Dispatcher::getInstance()->getController() == 'category')
                if (in_array(Tools::getValue('id_category'), $slider->options->categories) == false)
                    return;

            if (Dispatcher::getInstance()->getController() == 'cms') {
                $categories = array();
                $cms = array();
                foreach ($slider->options->cms as $c) {
                    if (strpos($c, 'category_') !== false)
                        $categories[] = str_replace('category_', '', $c);
                    if (strpos($c, 'cms_') !== false)
                        $cms[] = str_replace('cms_', '', $c);
                }
                if (!in_array(Tools::getValue('id_cms'), $cms) && !in_array(Tools::getValue('id_cms_category'), $categories))
                    return;
            }
        }

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
        $slides = Slides::getAll(array(self::$definition['primary'] => $this->id));
        if ($slides) {
            foreach ($slides as $slide) {
                $slide_obj = new Slides($slide[Slides::$definition['primary']]);
                $slide_obj->delete();
            }
        }
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

    public static function duplicate() {
        $slider = new Sliders(Tools::getValue(self::$definition['primary']));
        if (!is_object($slider))
            return;
        unset($slider->id);
        $slider->save();
        $parms = array();
        $parms[Sliders::$definition['primary']] = Tools::getValue(self::$definition['primary']);
        $slides = Slides::getAll($parms);
        $par = self::$definition['primary'];
        foreach ($slides as $slide_old) {
            $slide = new Slides($slide_old[Slides::$definition['primary']]);
            unset($slide->id);
            $slide->$par = $slider->id;
            $slide->save();
            //copy images
            $source = Slides::get_image_path(Tools::getValue(self::$definition['primary']));
            $dest = Slides::get_image_path($slider->id);
            @mkdir($dest);
            copy($source . $slide->image, $dest . $slide->image);
        }
    }

    public static function get_option_fields() {
        return array('mode', 'captions', 'autoControls', 'auto', 'infiniteLoop', 'hideControlOnEnd',
            'adaptiveHeight', 'slideWidth', 'minSlides', 'maxSlides', 'slideMargin', 'pager', 'pagerType',
            'pagerCustom', 'thumbnailWidth', 'ticker', 'tickerHover', 'speed', 'startSlide', 'randomStart',
            'useCSS', 'easing_jquery', 'easing_css', 'categories', 'cms', 'hooks', 'pause', 'autoHover', 'autoStart', 'controls');
    }

    /* Get all CMS blocks */

    public static function getAllCMSStructure($id_shop = false) {
        $categories = self::getCMSCategories();
        $id_shop = ($id_shop !== false) ? $id_shop : Context::getContext()->shop->id;
        $all = array();
        foreach ($categories as $value) {
            $array_key = 'category_' . $value['id_cms_category'];
            $value['name'] = str_repeat("- ", $value['level_depth']) . $value['name'];
            $value['id'] = $array_key;
            $all[$array_key] = $value;
            $pages = self::getCMSPages($value['id_cms_category'], $id_shop);
            foreach ($pages as $page) {
                $array_key = 'cms_' . $page['id_cms'];
                $page['name'] = str_repeat("&nbsp;&nbsp;", $value['level_depth']) . $page['meta_title'];
                $page['id'] = $array_key;
                $all[$array_key] = $page;
            }
        }
        return $all;
    }

    public static function getCMSPages($id_cms_category, $id_shop = false) {
        $id_shop = ($id_shop !== false) ? $id_shop : Context::getContext()->shop->id;

        $sql = 'SELECT c.`id_cms`, cl.`meta_title`, cl.`link_rewrite`
			FROM `' . _DB_PREFIX_ . 'cms` c
			INNER JOIN `' . _DB_PREFIX_ . 'cms_shop` cs
			ON (c.`id_cms` = cs.`id_cms`)
			INNER JOIN `' . _DB_PREFIX_ . 'cms_lang` cl
			ON (c.`id_cms` = cl.`id_cms`)
			WHERE c.`id_cms_category` = ' . (int) $id_cms_category . '
			AND cs.`id_shop` = ' . (int) $id_shop . '
			AND cl.`id_lang` = ' . (int) Context::getContext()->language->id . '
			AND c.`active` = 1
			ORDER BY `position`';

        return Db::getInstance()->executeS($sql);
    }

    public static function getCMSCategories($recursive = false, $parent = 0) {
        if ($recursive === false) {
            $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
					FROM `' . _DB_PREFIX_ . 'cms_category` bcp
					INNER JOIN `' . _DB_PREFIX_ . 'cms_category_lang` cl
					ON (bcp.`id_cms_category` = cl.`id_cms_category`)
					WHERE cl.`id_lang` = ' . (int) Context::getContext()->language->id;
            if ($parent)
                $sql .= ' AND bcp.`id_parent` = ' . (int) $parent;

            return Db::getInstance()->executeS($sql);
        }
        else {
            $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
					FROM `' . _DB_PREFIX_ . 'cms_category` bcp
					INNER JOIN `' . _DB_PREFIX_ . 'cms_category_lang` cl
					ON (bcp.`id_cms_category` = cl.`id_cms_category`)
					WHERE cl.`id_lang` = ' . (int) Context::getContext()->language->id;
            if ($parent)
                $sql .= ' AND bcp.`id_parent` = ' . (int) $parent;

            $results = Db::getInstance()->executeS($sql);
            $categories = array();
            foreach ($results as $result) {
                $sub_categories = self::getCMSCategories(true, $result['id_cms_category']);
                if ($sub_categories && count($sub_categories) > 0)
                    $result['sub_categories'] = $sub_categories;
                $categories[] = $result;
            }

            return isset($categories) ? $categories : false;
        }
    }

    //depraced from 1.1
    public static function get_hooks_by_id($id) {
        $sql = 'SELECT hook
			FROM `' . _DB_PREFIX_ . self::$definition['table'] . '_hook`
			WHERE `' . self::$definition['primary'] . '` = ' . $id;

        return (Db::getInstance()->executeS($sql));
    }

    public static function azexo_init($admin = false) {
        // just for slide builder
        Context::getContext()->controller->addJS(_PS_MODULE_DIR_ . 'sliderseverywhere/js/azexo_composer/underscore-min.js');
        Context::getContext()->controller->addCSS(_PS_MODULE_DIR_ . 'sliderseverywhere/js/azexo_composer/azexo_composer.css');

        if ($admin) {

            Context::getContext()->controller->addJS(_PS_MODULE_DIR_ . 'sliderseverywhere/js/azexo_composer/js/smoothscroll.js');
            Context::getContext()->controller->addJS(_PS_MODULE_DIR_ . 'sliderseverywhere/js/jquery-ui.min.js');
            Context::getContext()->controller->addJS(_PS_MODULE_DIR_ . 'sliderseverywhere/js/azexo_composer/jquery-waypoints/waypoints.min.js');
            Context::getContext()->controller->addJS(_PS_MODULE_DIR_ . 'sliderseverywhere/js/azexo_composer/azexo_param_types.min.js');
            Context::getContext()->controller->addCSS(_PS_MODULE_DIR_ . 'sliderseverywhere/css/bootstrap/bootstrap.css');
            Context::getContext()->controller->addJS(_PS_MODULE_DIR_ . 'sliderseverywhere/js/azexo_composer/init_admin.js');
            Context::getContext()->controller->addCSS(_PS_MODULE_DIR_ . 'sliderseverywhere/js/azexo_composer/azexo_composer_add.css');
        } else
            Context::getContext()->controller->addJS(_PS_MODULE_DIR_ . 'sliderseverywhere/js/azexo_composer/init_frontend.js');

        Context::getContext()->controller->addJS(_PS_MODULE_DIR_ . 'sliderseverywhere/js/azexo_composer/azexo_elements.min.js');
        Context::getContext()->controller->addJS(_PS_MODULE_DIR_ . 'sliderseverywhere/js/azexo_composer/azexo_composer.min.modified.js');
    }

    public static function getLiveEditToken() {
        return Tools::getAdminToken('sliderseverywhere' . (int) Tab::getIdFromClassName('sliderseverywhere')
                        . (is_object(Context::getContext()->employee) ? (int) Context::getContext()->employee->id :
                                Tools::getValue('id_employee')));
    }

}

?>
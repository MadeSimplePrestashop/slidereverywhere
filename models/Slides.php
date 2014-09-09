<?php

/**
 * Module Extended Order confirmation email 
 * 
 * @author 	kuzmany.biz
 * @copyright 	kuzmany.biz/prestashop
 * @license 	kuzmany.biz/prestashop
 * Reminder: You own a single production license. It would only be installed on one online store (or multistore)
 */
class Slides extends ObjectModel {

    public $id_sliderseverywhere_slides;
    public $id_sliderseverywhere;
    public $image;
    public $position;
    public $caption;
    public $target;
    public $video;
    public $url;
    public $active;
    private static $parent_definition;

    public function __construct($id = null, $id_lang = null, $id_shop = null) {
        self::$parent_definition = Sliders::$definition; // prevent php 5.3 bug
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
        'table' => 'sliderseverywhere_slides',
        'primary' => 'id_sliderseverywhere_slides',
        'multilang' => TRUE,
        'fields' => array(
            'image' => array('type' => self::TYPE_STRING),
            'id_sliderseverywhere' => array('type' => self::TYPE_STRING),
            'caption' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
            'url' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
            'video' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
            'target' => array('type' => self::TYPE_HTML, 'validate' => 'isString'),
            'position' => array('type' => self::TYPE_INT),
            'active' => array('type' => self::TYPE_INT)
        )
    );

    public static function getAll($parms = array()) {
        self::_init();
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from(self::$definition['table'], 'c');
        $sql->leftJoin(self::$definition['table'] . '_lang', 'l', 'c.' . self::$definition['primary'] . ' = l.' . self::$definition['primary'] . ' AND l.id_lang = ' . (int) Context::getContext()->language->id);
        if (Shop::isFeatureActive())
            $sql->innerJoin(self::$definition['table'] . '_shop', 's', 'c.' . self::$definition['primary'] . ' = s.' . self::$definition['primary'] . ' AND s.id_shop = ' . (int) Context::getContext()->shop->id);
        if (empty($parms) == false)
            foreach ($parms as $k => $p)
                $sql->where('' . $k . ' =\'' . $p . '\'');
        return Db::getInstance()->executeS($sql);
    }

    public function update($null_values = false) {
        $this->handle_image();
        parent::update();
    }

    public function add($autodate = true, $null_values = false) {
        $par = self::$parent_definition['primary'];
        $last_position = self::getLastPosition($this->$par);
        $this->position = $last_position + 1;
        $this->handle_image();
        parent::add($autodate, $null_values);
    }

    public function delete() {
        parent::delete();
        $par = self::$parent_definition['primary'];
        $this->cleanPositions($this->$par);
        @unlink(self::get_image_path($this->$par) . $this->image);
    }

    public function updatePosition($way, $position) {
        $sql = 'SELECT cp.`' . self::$definition['primary'] . '`, cp.`position`, cp.`' . self::$parent_definition['primary'] . '` 
			FROM `' . _DB_PREFIX_ . self::$definition['table'] . '` cp 
			WHERE cp.`' . self::$parent_definition['primary'] . '` = ' . (int) $this->id_sliderseverywhere . ' AND cp.' . self::$definition['primary'] . ' = ' . (int) $this->id . ' 
			ORDER BY cp.`position` ASC';
        if (!$res = Db::getInstance()->executeS($sql))
            return false;

        $moved_field = $res[0];

        if (!isset($moved_field) || !isset($position))
            return false;

        // < and > statements rather than BETWEEN operator
        // since BETWEEN is treated differently according to databases
        if (Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . self::$definition['table'] . '`
				SET `position`= `position` ' . ($way ? '- 1' : '+ 1') . '
				WHERE `position`
					' . ($way ? '> ' . (int) $moved_field['position'] . ' AND `position` <= ' . (int) $position : '< ' . (int) $moved_field['position'] . ' AND `position` >= ' . (int) $position) . '
					AND `' . self::$parent_definition['primary'] . '`=' . (int) $this->id_sliderseverywhere . ';') && Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . self::$definition['table'] . '`
				SET `position` = ' . (int) $position . '
				WHERE `' . self::$definition['primary'] . '` = ' . (int) $moved_field[self::$definition['primary']] . '
				AND `' . self::$parent_definition['primary'] . '`=' . (int) $this->id_sliderseverywhere . ';'))
            return self::cleanPositions((int) $this->id_sliderseverywhere);
        return false;
    }

    public static function cleanPositions($ident) {
        $sql = 'SELECT `' . self::$definition['primary'] . '` 
			FROM `' . _DB_PREFIX_ . self::$definition ['table'] . '` 
			WHERE `' . self::$parent_definition['primary'] . '` = ' . (int) $ident . ' 
			ORDER BY `position`';

        $result = Db::getInstance()->executeS($sql);
        for ($i = 0, $total = count($result); $i < $total; ++$i) {
            $sql = 'UPDATE `' . _DB_PREFIX_ . self::$definition['table'] . '` 
				SET `position` = ' . (int) $i . ' 
				WHERE `' . self::$parent_definition['primary'] . '` = ' . (int) $ident . '
				AND `' . self::$definition['primary'] . '` = ' . (int) $result[$i][self::$definition['primary']];
            Db::getInstance()->execute($sql);
        }
        return true;
    }

    public static function getLastPosition($ident) {
        $sql = 'SELECT MAX(position) 
			FROM `' . _DB_PREFIX_ . self::$definition['table'] . '`
			WHERE `' . self::$parent_definition['primary'] . '` = ' . (int) $ident;
        return (Db::getInstance()->getValue($sql));
    }

    public static function get_image_path($id_dir = null) {
        if ($id_dir == null)
            return dirname(__FILE__) . '/../img/';
        else
            return dirname(__FILE__) . '/../img/' . $id_dir . '/';
    }

    public function handle_image() {
        $par = self::$parent_definition['primary'];
        if (isset($_FILES['image']) && isset($_FILES['image']['tmp_name']) && !empty($_FILES['image']['tmp_name'])) {
            //dir 
            $dir = self::get_image_path(Tools::getValue(self::$parent_definition['primary']));
            $file_name = $_FILES['image']['name'];
            //$ext = substr($_FILES['image']['name'], strrpos($_FILES['image']['name'], '.') + 1);
            //$file_name = md5($_FILES['image']['name']) . '.' . $ext;
            if (!is_dir($dir))
                @mkdir($dir);
            if (file_exists($dir . $file_name))
                unlink($dir . $file_name);
            if ($error = ImageManager::validateUpload($_FILES['image']))
                $this->errors[] = $error;
            elseif (!($tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES['image']['tmp_name'], $tmp_name))
                return false;
            elseif (!ImageManager::resize($tmp_name, $dir . $file_name))
                $this->errors[] = $this->l('An error occurred while attempting to upload the image.');
            if (isset($tmp_name))
                unlink($tmp_name);

            $this->image = $file_name;
        }
    }

}

?>
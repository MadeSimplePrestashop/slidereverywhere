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

class AdminSlidersController extends ModuleAdminController {

    public function __construct() {

        $this->bootstrap = true;
        $this->show_toolbar = true;
        $this->show_toolbar_options = true;
        $this->show_page_header_toolbar = true;

        $this->table = Sliders::$definition['table'];
        $this->className = 'Sliders';

        $this->addRowAction('view');
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        parent::__construct();
    }

    public function initContent() {

        if (Tools::getIsset('view' . $this->table))
            if (Tools::getIsset(Sliders::$definition['primary']))
                Tools::redirectAdmin('index.php?controller=AdminSlides&' . Sliders::$definition['primary'] . '=' . (int) Tools::getValue(Sliders::$definition['primary']) . '&token=' . Tools::getAdminTokenLite('AdminSlides'));
            else
                $this->errors[] = Tools::displayError('Can\'t identify slider. Please repeat your choice.');
        parent::initContent();
    }

    public function renderForm() {

        $obj = $this->loadObject(true);
        if (!$obj)
            return;

        if (is_object($obj))
            $options = Tools::jsonDecode($obj->options);
        else
            $options = '';

        $easing = array("linear", "swing", "easeInQuad", "easeOutQuad", "easeInOutQuad", "easeInCubic", "easeOutCubic", "easeInOutCubic", "easeInQuart", "easeOutQuart", "easeInOutQuart", "easeInQuint", "easeOutQuint", "easeInOutQuint", "easeInSine", "easeOutSine", "easeInOutSine", "easeInExpo", "easeOutExpo", "easeInOutExpo", "easeInCirc", "easeOutCirc", "easeInOutCirc", "easeInElastic", "easeOutElastic", "easeInOutElastic", "easeInBack", "easeOutBack", "easeInOutBack", "easeInBounce", "easeOutBounce", "easeInOutBounce");
        foreach ($easing as $key => $easin) {
            unset($easing[$key]);
            $easing[$key]['name'] = $easin;
        }
        array_unshift($easing, array('name' => ''));
        $this->fields_form = array(
            'legend' => array(
                'tinymce' => true,
                'title' => $this->l('Add new slider'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => Sliders::$definition['primary'],
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Alias'),
                    'name' => 'alias'
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Slide mode'),
                    'name' => 'mode',
                    'desc' => $this->l('Type of transition between slides'),
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 'fade',
                                'name' => $this->l('Fade')
                            ),
                            array(
                                'id' => 'horizontal',
                                'name' => $this->l('Horizontal')
                            ),
                            array(
                                'id' => 'vertical',
                                'name' => $this->l('Vertical')
                            )
                        ),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'default_value' => isset($options->mode) ? $options->mode : ''
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Captions'),
                    'name' => 'captions',
                    'desc' => $this->l('Include image captions. Captions are derived from the image\'s title attribute'),
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
                    'default_value' => isset($options->captions) ? $options->captions : ''
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Auto slide controls'),
                    'name' => 'autoControls',
                    'desc' => $this->l('If true, "Start" / "Stop" controls will be added'),
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
                    'default_value' => isset($options->autoControls) ? $options->autoControls : ''
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Autoslide'),
                    'name' => 'auto',
                    'desc' => $this->l('Slides will automatically transition'),
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
                    'default_value' => isset($options->auto) ? $options->auto : ''
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Infinite loop'),
                    'name' => 'infiniteLoop',
                    'desc' => $this->l('If true, clicking "Next" while on the last slide will transition to the first slide and vice-versa'),
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
                    'default_value' => isset($options->infiniteLoop) ? $options->infiniteLoop : 1
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Hide control on start/end'),
                    'name' => 'hideControlOnEnd',
                    'desc' => $this->l('If true, "Next" control will be hidden on last slide and vice-versa. Note: Only used when infiniteLoop: false'),
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
                    'default_value' => isset($options->hideControlOnEnd) ? $options->hideControlOnEnd : ''
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Adaptive height'),
                    'name' => 'adaptiveHeight',
                    'desc' => $this->l('Dynamically adjust slider height based on each slide\'s height'),
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
                    'default_value' => isset($options->adaptiveHeight) ? $options->adaptiveHeight : ''
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Slide width'),
                    'desc' => $this->l('The width of each slide. This setting is required for all horizontal carousels!'),
                    'name' => 'slideWidth',
                    'suffix' => 'px',
                    'class' => 'fixed-width-xs',
                    'default_value' => isset($options->slideWidth) ? $options->slideWidth : ''
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Min slides'),
                    'desc' => $this->l('The minimum number of slides to be shown. Slides will be sized down if carousel becomes smaller than the original size.'),
                    'name' => 'minSlides',
                    'class' => 'fixed-width-xs',
                    'default_value' => isset($options->minSlides) ? $options->minSlides : ''
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Max slides'),
                    'desc' => $this->l('The maximum number of slides to be shown. Slides will be sized up if carousel becomes larger than the original size.'),
                    'name' => 'maxSlides',
                    'class' => 'fixed-width-xs',
                    'default_value' => isset($options->maxSlides) ? $options->maxSlides : ''
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Slide margin'),
                    'desc' => $this->l('Margin between each slide'),
                    'name' => 'slideMargin',
                    'suffix' => 'px',
                    'class' => 'fixed-width-xs',
                    'default_value' => isset($options->slideMargin) ? $options->slideMargin : ''
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Pager'),
                    'name' => 'pager',
                    'desc' => $this->l('If true, a pager will be added'),
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
                    'default_value' => isset($options->pager) ? $options->pager : ''
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Pager type'),
                    'name' => 'pagerType',
                    'desc' => $this->l('If \'full\', a pager link will be generated for each slide. If \'short\', a x / y pager will be used (ex. 1 / 5)'),
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 'full',
                                'name' => $this->l('full')
                            ),
                            array(
                                'id' => 'short',
                                'name' => $this->l('short')
                            ),
                        ),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'default_value' => isset($options->pagerType) ? $options->pagerType : ''
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Thumbnails pager'),
                    'name' => 'pagerCustom',
                    'desc' => $this->l('Parent element to be used as the pager. Not for use with dynamic carousels.'),
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
                    'default_value' => isset($options->pagerCustom) ? $options->pagerCustom : ''
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Thumbnail width for pager'),
                    'name' => 'thumbnailWidth',
                    'suffix' => 'px',
                    'class' => 'fixed-width-xs',
                    'default_value' => isset($options->thumbnailWidth) ? $options->thumbnailWidth : 100,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Ticker'),
                    'name' => 'ticker',
                    'desc' => $this->l('Use slider in ticker mode (similar to a news ticker)'),
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
                    'default_value' => isset($options->ticker) ? $options->ticker : ''
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Ticker hover'),
                    'name' => 'tickerHover',
                    'desc' => $this->l('Ticker will pause when mouse hovers over slider. Note: this functionality does NOT work if using CSS transitions!'),
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
                    'default_value' => isset($options->tickerHover) ? $options->tickerHover : ''
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Speed'),
                    'desc' => $this->l('Slide transition duration'),
                    'name' => 'speed',
                    'suffix' => 'ms',
                    'class' => 'fixed-width-xs',
                    'default_value' => isset($options->speed) ? $options->speed : ''
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Start slide'),
                    'desc' => $this->l('Starting slide index (zero-based)'),
                    'name' => 'startSlide',
                    'class' => 'fixed-width-xs',
                    'default_value' => isset($options->startSlide) ? $options->startSlide : ''
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Random start'),
                    'name' => 'randomStart',
                    'desc' => $this->l('Start slider on a random slider'),
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
                    'default_value' => isset($options->randomStart) ? $options->randomStart : ''
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Use CSS for effects'),
                    'name' => 'useCSS',
                    'desc' => $this->l('If true, CSS transitions will be used for horizontal and vertical slide animations (this uses native hardware acceleration). '),
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
                    'default_value' => isset($options->useCSS) ? $options->useCSS : ''
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Easing (useCSS disabled or video at slider)'),
                    'name' => 'easing_jquery',
                    'desc' => $this->l('The type of "easing" to use during transitions. '),
                    'options' => array(
                        'query' =>
                        $easing
                        ,
                        'id' => 'name',
                        'name' => 'name',
                    ),
                    'default_value' => isset($options->easing_jquery) ? $options->easing_jquery : ''
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Easing (useCSS enabled)'),
                    'name' => 'easing_css',
                    'desc' => $this->l('The type of "easing" to use during transitions. '),
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => '',
                                'name' => $this->l('None')
                            ),
                            array(
                                'id' => 'linear',
                                'name' => $this->l('Linear')
                            ),
                            array(
                                'id' => 'ease',
                                'name' => $this->l('Ease')
                            ),
                            array(
                                'id' => 'ease-in',
                                'name' => $this->l('Ease-in')
                            ),
                            array(
                                'id' => 'ease-out',
                                'name' => $this->l('Ease-out')
                            ),
                            array(
                                'id' => 'ease-out',
                                'name' => $this->l('Ease-out')
                            ),
                            array(
                                'id' => 'ease-in-out',
                                'name' => $this->l('Ease-in-out')
                            ),
                            array(
                                'id' => 'cubic-bezier(n,n,n,n)',
                                'name' => $this->l('Cubic-bezier(n,n,n,n)')
                            )
                        ),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'default_value' => isset($options->easing_css) ? $options->easing_css : ''
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

        $this->page_header_toolbar_btn['save'] = array(
            'href' => 'javascript:$("#' . $this->table . '_form").submit();',
            'desc' => $this->l('Save')
        );
        $this->page_header_toolbar_btn['save-and-preview'] = array(
            'short' => 'SaveAndStay',
            'href' => 'javascript:$("#' . $this->table . '_form").attr("action", $("#' . $this->table . '_form").attr("action")+"&submitAddformAndPreview");$("#' . $this->table . '_form").submit();',
            'desc' => $this->l('Save and preview'),
            'force_desc' => true,
        );

        return parent::renderForm();
    }

    public function renderList() {

        $this->fields_list = array(
            'id_sliderseverywhere' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'width' => 25,
                'orderby' => false,
                'search' => false,
            ),
            'alias' => array(
                'title' => $this->l('Alias'),
                'type' => 'text',
                'orderby' => false,
                'search' => false
            ),
            'alias' => array(
                'title' => $this->l('Tag'),
                'type' => 'text',
                'orderby' => false,
                'search' => false,
                'callback' => 'getTag'
            ),
            'options' => array(
                'title' => $this->l('Test'),
                'type' => 'text',
                'orderby' =>
                false,
                'search' => false,
                'callback' => 'getTest'
            )
        );


        $this->page_header_toolbar_btn['new'] = array(
            'href' => self::$currentIndex . '&add' . $this->table . '&token=' . $this->token,
            'desc' => $this->l('Add new slider'),
            'icon' => 'process-icon-new'
        );

        return parent::renderList();
    }

    public function renderPageHeaderToolbar() {
        $id_form = (int) Tools::getValue('id_form');

        if ($this->display == 'list') {
            $this->page_header_toolbar_btn['new'] = array(
                'href' => self::$currentIndex . '&add' . $this->table . '&token=' . $this->token,
                'desc' => $this->l('Add new form'),
                'icon' => 'process-icon-new'
            );
            if (FormClass::hasForms($this->context->shop->id))
                $this->page_header_toolbar_btn['newField'] = array(
                    'href' => self::$currentIndex . '&addform_field&id_form=' . (int) $id_form . '&token=' . $this->token,
                    'desc' => $this->l('Add new Field'),
                    'icon' => 'process-icon-edit'
                );
        } else if ($this->display == 'add') {
            $this->page_header_toolbar_btn['save'] = array(
                'href' => 'javascript:$("#' . $this->table . '_form").submit();',
                'desc' => $this->l('Save')
            );
            $this->page_header_toolbar_btn['save-and-stay'] = array(
                'short' => 'SaveAndStay',
                'href' => 'javascript:$("#' . $this->table . '_form").attr("action", $("#' . $this->table . '_form").attr("action")+"&submitAddformAndStay");$("#' . $this->table . '_form").submit();',
                'desc' => $this->l('Save and stay'),
                'force_desc' => true,
            );
        } else if ($this->display == 'edit') {
            $this->page_header_toolbar_btn['duplicate'] = array(
                'href' => self::$currentIndex . '&id_form=' . Tools::getValue('id_form') . '&duplicateform&token=' . $this->token,
                'desc' => $this->l('Duplicate'),
                'class' => 'toolbar-duplicate'
            );
            $this->page_header_toolbar_btn['save'] = array(
                'href' => 'javascript:$("#' . $this->table . '_form").submit();',
                'desc' => $this->l('Save')
            );
            $this->page_header_toolbar_btn ['save-and-stay'] = array(
                'short' => 'SaveAndStay',
                'href' => 'javascript:$("#' . $this->table . '_form").attr("action", $("#' . $this->table . '_form").attr("action")+"&submitAddformAndStay");$("#' . $this->table . '_form").submit();', 'desc' => $this->l('Save and stay'),
                'force_desc' => true,
            );
        }
        $this->page_header_toolbar_title = implode(' ' . Configuration::get('PS_NAVIGATION_PIPE') . ' ', $this->toolbar_title);

        if (is_array($this->page_header_toolbar_btn) && $this->page_header_toolbar_btn instanceof Traversable || trim($this->page_header_toolbar_title) != '')
            $this->show_page_header_toolbar = true;

        $template = $this->context->smarty->createTemplate(
                $this->context->smarty->getTemplateDir(0) . DIRECTORY_SEPARATOR
                . 'page_header_toolbar.tpl', $this->context->smarty);

        $this->context->smarty->assign(array(
            'show_page_header_toolbar' => $this->show_page_header_toolbar
            ,
            'title' => $this->page_header_toolbar_title,
            'toolbar_btn' => $this->page_header_toolbar_btn,
            'page_header_toolbar_btn' => $this->page_header_toolbar_btn,
            'page_header_toolbar_title' => $this->toolbar_title,
        ));

        return $template->fetch();
    }

    //render image at renderList
    public function getTest($echo, $row) {
        return '{sliderseverywhere alias=\'' . $row['alias'] . '\'}';
    }

    public function getTag($echo, $row) {
        return '{sliderseverywhere alias=\'' . $row['alias'] . '\'}';
    }

}

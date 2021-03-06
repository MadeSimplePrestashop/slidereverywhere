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

class AdminSlidersController extends ModuleAdminController
{

    public function __construct()
    {

        $this->bootstrap = true;
        $this->show_toolbar = true;
        $this->show_toolbar_options = true;
        $this->show_page_header_toolbar = true;

        $this->table = Sliders::$definition['table'];
        $this->className = 'Sliders';

        $this->addRowAction('view');
        $this->addRowAction('edit');
        $this->addRowAction('duplicate');
        $this->addRowAction('delete');

        Shop::addTableAssociation($this->table, array('type' => 'shop'));

        parent::__construct();
    }

    public function initContent()
    {

        if (Tools::getIsset('duplicate' . $this->table))
            Sliders::duplicate();
        elseif (Tools::getIsset('view' . $this->table))
            if (Tools::getIsset(Sliders::$definition['primary']))
                Tools::redirectAdmin('index.php?controller=AdminSlides&' . Sliders::$definition['primary'] . '=' . (int) Tools::getValue(Sliders::$definition['primary']) . '&token=' . Tools::getAdminTokenLite('AdminSlides'));
            else
                $this->errors[] = Tools::displayError('Can\'t identify slider. Please repeat your choice.');
        parent::initContent();
    }

    public function postProcess()
    {
        parent::postProcess();
        if (Tools::getIsset('delete' . $this->table))
            Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminSliders'));
        elseif (Tools::isSubmit('submitAdd' . $this->table))
            if (Tools::getIsset('submitPreview'))
                Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminSlides') . '&' . Sliders::$definition['primary'] . '=' . $this->object->id . '#preview');
            elseif (Tools::getIsset('submitStay'))
                Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminSliders') . '&' . Sliders::$definition['primary'] . '=' . $this->object->id . '&update' . $this->table);
            else
                Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminSliders'));
    }

    public function renderForm()
    {

        $obj = $this->loadObject(true);
        if (!$obj)
            return;

        $par = Sliders::$definition['primary'];

        if (is_object($obj))
            $options = Tools::jsonDecode($obj->options);
        else
            $options = '';
        $easing = array("linear", "swing", "easeInQuad", "easeOutQuad", "easeInOutQuad", "easeInCubic", "easeOutCubic", "easeInOutCubic", "easeInQuart", "easeOutQuart", "easeInOutQuart", "easeInQuint", "easeOutQuint", "easeInOutQuint", "easeInSine", "easeOutSine", "easeInOutSine", "easeInExpo", "easeOutExpo", "easeInOutExpo", "easeInCirc", "easeOutCirc", "easeInOutCirc", "easeInElastic", "easeOutElastic", "easeInOutElastic", "easeInBack", "easeOutBack", "easeInOutBack", "easeInBounce", "easeOutBounce", "easeInOutBounce");
        foreach ($easing as $key => $easin) {
            unset($easing[$key]);
            $easing[$key]['name'] = $easin;
        }

        $selected_categories = array();
        if (isset($options->categories) && empty($options->categories) == false)
            $selected_categories = $options->categories;

        $root_category = Category::getRootCategory();
        $root_category = array('id_category' => $root_category->id, 'name' => $root_category->name);
        array_unshift($easing, array('name' => ''));
        $this->fields_form = array(
            'legend' => array(
                'tinymce' => true,
                'title' => $this->l('Slider'),
                'icon' => 'icon-cogs'
            ),
            'tabs' => array(
                'options' => $this->l('Slider'),
                'carousel' => $this->l('Carousel/Ticker'),
                'pager' => $this->l('Ticker'),
                'captions' => $this->l('Captions'),
                'style' => $this->l('Style'),
                'display' => $this->l('Display'),
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => Sliders::$definition['primary'],
                    'tab' => 'options'
                ),
                array(
                    'tab' => 'options',
                    'type' => 'text',
                    'label' => $this->l('Alias'),
                    'name' => 'alias',
                    'required' => true
                ),
                array(
                    'tab' => 'options',
                    'type' => 'select',
                    'label' => $this->l('Slide mode'),
                    'name' => 'mode',
                    'hint' => $this->l('Type of transition between slides'),
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
                    'tab' => 'options',
                    'type' => 'switch',
                    'label' => $this->l('Controls'),
                    'name' => 'controls',
                    'hint' => $this->l('If true, "Next" / "Prev" controls will be added'),
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
                    'default_value' => isset($options->controls) ? $options->controls : true
                ),
                array(
                    'tab' => 'options',
                    'type' => 'switch',
                    'label' => $this->l('Autoslide'),
                    'name' => 'auto',
                    'hint' => $this->l('Slides will automatically transition'),
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
                    'tab' => 'options',
                    'type' => 'switch',
                    'label' => $this->l('Auto slide controls'),
                    'name' => 'autoControls',
                    'hint' => $this->l('If true, "Start" / "Stop" controls will be added'),
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
                    'tab' => 'options',
                    'type' => 'switch',
                    'label' => $this->l('Auto start'),
                    'name' => 'autoStart',
                    'hint' => $this->l('Auto show starts playing on load. If false, slideshow will start when the "Start" control is clicked'),
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
                    'default_value' => isset($options->autoStart) ? $options->autoStart : ''
                ),
                array(
                    'tab' => 'options',
                    'type' => 'switch',
                    'label' => $this->l('Auto hover'),
                    'name' => 'autoHover',
                    'hint' => $this->l('Auto show will pause when mouse hovers over slider'),
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
                    'default_value' => isset($options->autoHover) ? $options->autoHover : ''
                ),
                array(
                    'tab' => 'options',
                    'type' => 'switch',
                    'label' => $this->l('Infinite loop'),
                    'name' => 'infiniteLoop',
                    'hint' => $this->l('If true, clicking "Next" while on the last slide will transition to the first slide and vice-versa'),
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
                    'tab' => 'options',
                    'type' => 'switch',
                    'label' => $this->l('Hide control on start/end'),
                    'name' => 'hideControlOnEnd',
                    'hint' => $this->l('If true, "Next" control will be hidden on last slide and vice-versa. Note: Only used when infiniteLoop: false'),
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
                    'tab' => 'options',
                    'type' => 'switch',
                    'label' => $this->l('Adaptive height'),
                    'name' => 'adaptiveHeight',
                    'hint' => $this->l('Dynamically adjust slider height based on each slide\'s height'),
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
                    'tab' => 'carousel',
                    'type' => 'html',
                    'name' => 'html_data_carousel',
                    'html_content' => '<strong>' . $this->l('Hint') . ':</strong> ' . $this->l("You can use carousel/ticker only with horizontal/vertical slide mode.")
                ),
                array(
                    'tab' => 'carousel',
                    'type' => 'text',
                    'label' => $this->l('Slide width'),
                    'hint' => $this->l('The width of each slide. This setting is required for all horizontal carousels!'),
                    'name' => 'slideWidth',
                    'suffix' => 'px',
                    'class' => 'fixed-width-sm',
                    'default_value' => isset($options->slideWidth) ? $options->slideWidth : ''
                ),
                array(
                    'tab' => 'carousel',
                    'type' => 'text',
                    'label' => $this->l('Min slides'),
                    'hint' => $this->l('The minimum number of slides to be shown. Slides will be sized down if carousel becomes smaller than the original size.'),
                    'name' => 'minSlides',
                    'class' => 'fixed-width-sm',
                    'default_value' => isset($options->minSlides) ? $options->minSlides : ''
                ),
                array(
                    'tab' => 'carousel',
                    'type' => 'text',
                    'label' => $this->l('Max slides'),
                    'hint' => $this->l('The maximum number of slides to be shown. Slides will be sized up if carousel becomes larger than the original size.'),
                    'name' => 'maxSlides',
                    'class' => 'fixed-width-sm',
                    'default_value' => isset($options->maxSlides) ? $options->maxSlides : ''
                ),
                array(
                    'tab' => 'options',
                    'type' => 'text',
                    'label' => $this->l('Slide margin'),
                    'hint' => $this->l('Margin between each slide'),
                    'name' => 'slideMargin',
                    'suffix' => 'px',
                    'class' => 'fixed-width-sm',
                    'default_value' => isset($options->slideMargin) ? $options->slideMargin : ''
                ),
                array(
                    'tab' => 'pager',
                    'type' => 'switch',
                    'label' => $this->l('Pager'),
                    'name' => 'pager',
                    'hint' => $this->l('If true, a pager will be added'),
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
                    'default_value' => isset($options->pager) ? $options->pager : 1
                ),
                array(
                    'tab' => 'pager',
                    'type' => 'select',
                    'label' => $this->l('Pager type'),
                    'name' => 'pagerType',
                    'hint' => $this->l('If \'full\', a pager link will be generated for each slide. If \'short\', a x / y pager will be used (ex. 1 / 5)'),
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
                    'tab' => 'pager',
                    'type' => 'switch',
                    'label' => $this->l('Thumbnails pager'),
                    'name' => 'pagerCustom',
                    'hint' => $this->l('Parent element to be used as the pager. Not for use with dynamic carousels.'),
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
                    'tab' => 'pager',
                    'type' => 'text',
                    'label' => $this->l('Thumbnail width for pager'),
                    'name' => 'thumbnailWidth',
                    'suffix' => 'px',
                    'class' => 'fixed-width-sm',
                    'default_value' => isset($options->thumbnailWidth) ? $options->thumbnailWidth : 100,
                ),
                array(
                    'tab' => 'carousel',
                    'type' => 'html',
                    'name' => 'html_data',
                    'html_content' => '<hr><strong>' . $this->l('Ticker mode') . ':</strong> ' . $this->l("Don 't forget edit the speed on options tab, because default speed is only 500ms.")
                ),
                array(
                    'tab' => 'carousel',
                    'type' => 'switch',
                    'label' => $this->l('Ticker'),
                    'name' => 'ticker',
                    'hint' => $this->l('Use slider in ticker mode (similar to a news ticker)'),
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
                    'tab' => 'carousel',
                    'type' => 'switch',
                    'label' => $this->l('Ticker hover'),
                    'name' => 'tickerHover',
                    'hint' => $this->l('Ticker will pause when mouse hovers over slider. Note: this functionality does NOT work if using CSS transitions!'),
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
                    'tab' => 'options',
                    'type' => 'text',
                    'label' => $this->l('Pause'),
                    'hint' => $this->l('The amount of time (in ms) between each auto transition. Default 4000ms'),
                    'name' => 'pause',
                    'suffix' => 'ms',
                    'class' => 'fixed-width-sm',
                    'default_value' => isset($options->pause) ? $options->pause : ''
                ),
                array(
                    'tab' => 'options',
                    'type' => 'text',
                    'label' => $this->l('Speed'),
                    'hint' => $this->l('Slide transition duration'),
                    'name' => 'speed',
                    'suffix' => 'ms',
                    'class' => 'fixed-width-sm',
                    'default_value' => isset($options->speed) ? $options->speed : ''
                ),
                array(
                    'tab' => 'options',
                    'type' => 'text',
                    'label' => $this->l('Start slide'),
                    'hint' => $this->l('Starting slide index (zero-based)'),
                    'name' => 'startSlide',
                    'class' => 'fixed-width-sm',
                    'default_value' => isset($options->startSlide) ? $options->startSlide : ''
                ),
                array(
                    'tab' => 'options',
                    'type' => 'switch',
                    'label' => $this->l('Random start'),
                    'name' => 'randomStart',
                    'hint' => $this->l('Start slider on a random slider'),
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
                    'tab' => 'options',
                    'type' => 'switch',
                    'label' => $this->l('Use CSS for effects'),
                    'name' => 'useCSS',
                    'hint' => $this->l('If true, CSS transitions will be used for horizontal and vertical slide animations (this uses native hardware acceleration). '),
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
                    'tab' => 'options',
                    'type' => 'select',
                    'label' => $this->l('Easing'),
                    'name' => 'easing_jquery',
                    'hint' => $this->l('Only If useCSS is disabled or video is in slider'),
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
                    'tab' => 'options',
                    'type' => 'select',
                    'label' => $this->l('Easing (useCSS enabled)'),
                    'name' => 'easing_css',
                    'hint' => $this->l('Only If useCSS is enabled.'),
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => '',
                                'name' => ''
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
                array(
                    'tab' => 'captions',
                    'type' => 'switch',
                    'label' => $this->l('Captions'),
                    'name' => 'captions',
                    'hint' => $this->l("Include image captions. Captions are derived from the image's title attribute"),
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
                    'tab' => 'captions',
                    'type' => 'select',
                    'label' => $this->l('Width'),
                    'name' => 'captionWidth',
                    'default_value' => isset($options->captionWidth) ? $options->captionWidth : 'width:100%;',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 'width:auto;',
                                'name' => $this->l('Auto')
                            ),
                            array(
                                'id' => 'width:100%;',
                                'name' => $this->l('Full width')
                            ),
                        ),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                ),
                array(
                    'tab' => 'captions',
                    'type' => 'color',
                    'label' => $this->l('Background color'),
                    'name' => 'captionBackgroundColor',
                    'default_value' => isset($options->captionBackgroundColor) ? $options->captionBackgroundColor : '#ffffff',
                ),
                array(
                    'tab' => 'captions',
                    'type' => 'color',
                    'label' => $this->l('Font color'),
                    'name' => 'captionFontColor',
                    'default_value' => isset($options->captionFontColor) ? $options->captionFontColor : '#555555',
                ),
                array(
                    'tab' => 'captions',
                    'type' => 'text',
                    'class' => 'fixed-width-sm',
                    'label' => $this->l('Font size'),
                    'name' => 'captionFontSize',
                    'default_value' => isset($options->captionFontSize) ? $options->captionFontSize : '12px',
                ),
                array(
                    'tab' => 'captions',
                    'type' => 'select',
                    'label' => $this->l('Position'),
                    'name' => 'captionPosition',
                    'default_value' => isset($options->captionPosition) ? $options->captionPosition : ' ',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 'left:0; bottom:0; right:auto; top:auto;',
                                'name' => $this->l('left bottom')
                            ),
                            array(
                                'id' => 'left:0; top:0; bottom:auto; right:auto;',
                                'name' => $this->l('left top')
                            ),
                            array(
                                'id' => 'right:0; top:0; left:auto; bottom:auto;',
                                'name' => $this->l('right top')
                            ),
                            array(
                                'id' => 'right:0; bottom:0; top:auto; left:auto;',
                                'name' => $this->l('right bottom')
                            ),
                        ),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                ),
                array(
                    'tab' => 'captions',
                    'type' => 'text',
                    'label' => $this->l('Opacity'),
                    'name' => 'captionOpacity',
                    'suffix' => $this->l('from 0 to 1'),
                    'class' => 'fixed-width-sm',
                    'default_value' => isset($options->captionOpacity) ? $options->captionOpacity : '1',
                ),
                array(
                    'tab' => 'captions',
                    'type' => 'text',
                    'label' => $this->l('Padding'),
                    'name' => 'captionPadding',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('top right bottom left'),
                    'default_value' => isset($options->captionPadding) ? $options->captionPadding : '10px 10px 10px 10px',
                ),
                array(
                    'tab' => 'captions',
                    'type' => 'text',
                    'label' => $this->l('Margin'),
                    'name' => 'captionMargin',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('top right bottom left'),
                    'default_value' => isset($options->captionMargin) ? $options->captionMargin : '0px 0px 0px 0px',
                ),
                array(
                    'tab' => 'captions',
                    'type' => 'text',
                    'label' => $this->l('CSS style for caption'),
                    'desc' => $this->l('For advanced user'),
                    'name' => 'css',
                    'default_value' => isset($options->css) ? $options->css : ''
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right',
                'name' => 'submit',
            )
        );

        $positions = array();
        $href = $this->context->shop->getBaseUrl() . '?se_live_edit_token=' . Sliders::getLiveEditToken() . '&id_employee=' . $this->context->employee->id;
        $positions[] = '<div class="col-sm-4">';
        $positions[] = '<a onclick="if(!confirm(\'' . $this->l('Web page will be opened in new window in mode for select website position. Do you want continue?') . '\')) return false"  target="_blank" href="' . $href . '" id="select_position"><button   type="button" class="btn btn-default" >' . $this->l('select element from website') . '</button></a>';
        $positions[] = '</div>';
        $this->fields_value['position'] = implode('', $positions);
        $this->fields_form['input'][] = array(
            'tab' => 'display',
            'type' => 'free',
            'name' => 'position',
            'label' => $this->l('Website position picker')
        );
        $this->fields_value['element'] = '<input value="' . (isset($options->element) ? $options->element : '' ) . '" name="element" id="element" type="text">';
        $this->fields_form['input'][] = array(
            'class' => 'element',
            'tab' => 'display',
            'type' => 'free',
            'name' => 'element',
            'desc' => $this->l('If leave empty, slider will not displayed'),
            'label' => $this->l('Selected element'),
        );

        $this->fields_form['input'][] = array(
            'tab' => 'display',
            'type' => 'radio',
            'label' => $this->l('Insert slider'),
            'name' => 'insert',
            'values' => array(
                array(
                    'id' => 'after',
                    'value' => 'after',
                    'label' => $this->l('After selected element')
                ),
                array(
                    'id' => 'before',
                    'value' => 'before',
                    'label' => $this->l('Before selected element')
                ),
                array(
                    'id' => 'prepend',
                    'value' => 'prepend',
                    'label' => $this->l('Prepend to selected element')
                ),
                array(
                    'id' => 'append',
                    'value' => 'append',
                    'label' => $this->l('Append to selected element')
                ),
                array(
                    'id' => 'replace',
                    'value' => 'replace',
                    'label' => $this->l('Replace selected element')
                )
            ),
            'default_value' => isset($options->insert) ? $options->insert : ''
        );
        $this->fields_form['input'][] = array(
            'type' => 'select',
            'label' => $this->l('Display on controllers'),
            'name' => 'controllers[]',
            'class' => 'chosen element',
            'multiple' => true,
            'options' => array(
                'query' => $this->getControllers(),
                'id' => 'name',
                'name' => 'name'
            ),
            'tab' => 'display',
            'default_value' => isset($options->controllers) ? $options->controllers : ''
        );

        $this->fields_form['input'][] = array(
            'type' => 'select',
            'label' => $this->l('Display on products page'),
            'name' => 'products[]',
            'class' => 'chosen element',
            'multiple' => true,
            'options' => array(
                'query' => Product::getProducts((int) Context::getContext()->language->id, 0, 1000, 'p.id_product', 'asc', false, true),
                'id' => 'id_product',
                'name' => 'name'
            ),
            'tab' => 'display',
            'default_value' => isset($options->products) ? $options->products : ''
        );

        $this->fields_form['input'][] = array(
            'tab' => 'display',
            'type' => 'categories',
            'label' => $this->l('Categories'),
            'name' => 'categories',
            'desc' => $this->l('Empty is disabled.'),
            'hint' => $this->l('Empty is disabled.'),
            'tree' => array(
                'use_search' => false,
                'id' => 'categoryBox',
                'use_checkbox' => true,
                'selected_categories' => $selected_categories,
            ),
            'values' => array(
                'trads' => array(
                    'Root' => $root_category,
                    'selected' => $this->l('Selected'),
                    'Collapse All' => $this->l('Collapse All'),
                    'Expand All' => $this->l('Expand All'),
                    'Check All' => $this->l('Check All'),
                    'Uncheck All' => $this->l('Uncheck All')
                ),
                'selected_cat' => $selected_categories,
                'input_name' => 'categories[]',
                'use_radio' => false,
                'use_search' => false,
                'disabled_categories' => array(),
                'top_category' => Category::getTopCategory(),
                'use_context' => true,
            )
        );


        $this->fields_form['input'][] = array(
            'tab' => 'display',
            'type' => 'select',
            'multiple' => true,
            'size' => 7,
            'label' => $this->l('CMS categories and pages'),
            'name' => 'cms[]',
            'hint' => $this->l('It\'s optional.'),
            'desc' => $this->l('Optional. CTRL+click for select/unselect more options'),
            'options' => array(
                'query' => Sliders::getAllCMSStructure(),
                'id' => 'id',
                'name' => 'name'
            )
            , 'default_value' => isset($options->cms) ? $options->cms : ''
        );

        $html_data = array();
        $html_data[] = '<div style="display:none"><select multiple="multiple" name="hooks[]">';
        foreach ($this->module->hooks as $hook) {
            $html_data[] = '<option ' . (isset($options->hooks) && is_array($options->hooks) && in_array($hook, $options->hooks) ? 'selected="selected"' : '') . ' value="' . $hook . '">' . $hook . '</option>';
        }
        $html_data[] = '</select></div>';
        $this->fields_form['input'][] = array(
            'tab' => 'display',
            'type' => 'html',
            'name' => 'html_data_hooks',
            'html_content' => implode('', $html_data)
        );

        $this->fields_form['input'][] = array(
            'tab' => 'style',
            'type' => 'text',
            'label' => 'Border width',
            'name' => 'sliderBorderWidth',
            'class' => 'input fixed-width-sm',
            'default_value' => isset($options->sliderBorderWidth) ? $options->sliderBorderWidth : '5px',
        );

        $this->fields_form['input'][] = array(
            'tab' => 'style',
            'type' => 'select',
            'label' => $this->l('Border Style'),
            'name' => 'sliderBorderStyle',
            'options' => array(
                'query' => array(
                    array(
                        'value' => 'none',
                        'label' => $this->l('None')
                    ),
                    array(
                        'value' => 'hidden',
                        'label' => $this->l('Hidden')
                    ),
                    array(
                        'value' => 'dotted',
                        'label' => $this->l('Dotted')
                    ),
                    array(
                        'value' => 'solid',
                        'label' => $this->l('Solid')
                    ),
                    array(
                        'value' => 'double',
                        'label' => $this->l('double')
                    ),
                    array(
                        'value' => 'groove',
                        'label' => $this->l('Groove')
                    ),
                    array(
                        'value' => 'ridge',
                        'label' => $this->l('Ridge')
                    ),
                    array(
                        'value' => 'inset',
                        'label' => $this->l('Inset')
                    ),
                    array(
                        'value' => 'outset',
                        'label' => $this->l('Outset')
                    )
                ),
                'id' => 'value',
                'name' => 'label'
            ),
            'default_value' => isset($options->sliderBorderStyle) ? $options->sliderBorderStyle : 'solid',
        );


        $this->fields_form['input'][] = array(
            'tab' => 'style',
            'type' => 'color',
            'label' => $this->l('Border color'),
            'name' => 'sliderBorderColor',
            'default_value' => isset($options->sliderBorderColor) ? $options->sliderBorderColor : '#ffffff',
        );

        $this->fields_form['input'][] = array(
            'tab' => 'style',
            'type' => 'color',
            'label' => $this->l('Background color'),
            'name' => 'sliderBackgroundColor',
            'default_value' => isset($options->sliderBackgroundColor) ? $options->sliderBackgroundColor : '#ffffff',
        );

        $this->fields_form['input'][] = array(
            'tab' => 'style',
            'type' => 'text',
            'label' => 'Inline CSS style',
            'name' => 'sliderCSS',
            'desc' => $this->l('For advanced user'),
            'default_value' => isset($options->sliderCSS) ? $options->sliderCSS : 'box-shadow: 0 0 5px #ccc;',
        );

//        $query = array();
//        foreach ($this->module->hooks as $hook)
//            $query[]['name'] = $hook;
//        $this->fields_form['input'][] = array(
//            'tab' => 'display',
//            'readonly' => 'readonly',
//            'type' => 'select',
//            'multiple' => true,
//            'size' => 7,
//            'label' => $this->l('Hooks'),
//            'name' => 'hooks[]',
//            'hint' => $this->l('It\'s optional. Choose a display position. More about hooks in documentation.'),
//            'desc' => $this->l('Disabled in 1.3 version. Use website position picker above.'),
//            'options' => array(
//                'query' => $query,
//                'id' => 'name',
//                'name' => 'name'
//            )
//            , 'default_value' => $options->hooks
//        );



        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = array(
                'tab' => 'display',
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

//        $this->page_header_toolbar_btn['save-and-preview'] = array(
//            'short' => 'SaveAndStay',
//            'href' => 'javascript:$("#' . $this->table . '_form").attr("action", $("#' . $this->table . '_form").attr("action")+"&submitPreview");$("#' . $this->table . '_form button:submit").click();',
//            'desc' => $this->l('Save and add slides'),
//            'force_desc' => true,
//        );


        $this->page_header_toolbar_btn['new'] = array(
            'href' => $this->context->link->getAdminLink('AdminSlides') . '&' . Sliders::$definition['primary'] . '=' . $obj->$par,
            'desc' => $this->l('Go to slides'),
            'icon' => 'process-icon-configure'
        );
        $this->page_header_toolbar_btn['edit'] = array(
            'href' => self::$currentIndex . '&token=' . $this->token,
            'desc' => $this->l('Return to sliders'),
            'icon' => 'process-icon-cancel'
        );

        $this->tpl_list_vars['title'] = 'test';

        return parent::renderForm();
    }

    public function renderList()
    {

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
                'title' => $this->l('Slides'),
                'type' => 'text',
                'orderby' =>
                false,
                'search' => false,
                'callback' => 'getSlides'
            )
        );


        $this->page_header_toolbar_btn['new'] = array(
            'href' => self::$currentIndex . '&add' . $this->table . '&token=' . $this->token,
            'desc' => $this->l('Add new slider'),
            'icon' => 'process-icon-new'
        );

        return parent::renderList();
    }

//render image at renderList
    public function getSlides($echo, $row)
    {
        $parms = array($echo);
        array_shift($parms);
        $parms[Sliders::$definition['primary']] = $row[Sliders::$definition['primary']];
        $slides = Slides::getAll($parms);
        return count($slides);
    }

    public function getTag($echo)
    {
        return '{sliderseverywhere alias=\'' . $echo . '\'}';
    }

    private function getControllers()
    {
        $cache_id = __CLASS__ . __FUNCTION__ . '11';

        if (Cache::getInstance()->exists($cache_id)) {
            $controllers_array = Cache::getInstance()->get($cache_id);
        } else {

            // @todo do something better with controllers
            $controllers = Dispatcher::getControllers(_PS_FRONT_CONTROLLER_DIR_);
            ksort($controllers);
            foreach (array_keys($controllers) as $k) {
                $controllers_array[]['name'] = $k;
            }

            $modules_controllers_type = array('front' => $this->l('Front modules controller'));
            foreach (array_keys($modules_controllers_type) as $type) {
                $all_modules_controllers = Dispatcher::getModuleControllers($type);
                foreach ($all_modules_controllers as $module => $modules_controllers) {
                    foreach ($modules_controllers as $cont) {
                        $controllers_array[]['name'] = 'module-' . $module . '-' . $cont;
                    }
                }
            }

            $timeout = 3600 * 24;
            Cache::getInstance()->set($cache_id, $controllers_array, $timeout);
        }
        return $controllers_array;
    }
}

(function($) {
    var p = '';
    var fp = '';
    if ('azexo_prefix' in window) {
        p = window.azexo_prefix;
        fp = window.azexo_prefix.replace('-', '_');
    }

    function t(text) {
        if ('azexo_t' in window) {
            return window.azexo_t(text);
        } else {
            return text;
        }
    }

    var target_options = {
        '_self': t('Same window'),
        '_blank': t('New window'),
    };

    var azexo_elements = [
        {
            base: 'az_text',
            name: t('Text'),
            icon: 'fa fa-list-alt',
            description: t('A block of text with WYSIWYG editor'),
            params: [
                {
                    type: 'textarea',
                    heading: t('Text'),
                    param_name: 'content',
                    value: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                },
            ],
            show_settings_on_create: true,
            is_container: true,
            has_content: true,
            render: function($, p, fp) {
                
                this.dom_element = $('<div class="az-element az-text ' + this.attrs['el_class'] + '" style="' + this.attrs['style'] + '">' + this.attrs['content'] + '</div>');
                this.dom_content_element = this.dom_element;
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_icon',
            name: t('Icon'),
            icon: 'fa fa-info',
            description: t('Vector icon'),
            params: [
                {
                    type: 'icon',
                    heading: t('Icon'),
                    param_name: 'icon',
                },
                {
                    type: 'integer_slider',
                    heading: t('Icon size'),
                    param_name: 'size',
                    description: t('Font size of icon in px.'),
                },
                {
                    type: 'link',
                    heading: t('Link'),
                    param_name: 'link',
                    description: t('Icon link (url).'),
                },
                {
                    type: 'dropdown',
                    heading: t('Link target'),
                    param_name: 'link_target',
                    description: t('Select where to open link.'),
                    value: target_options,
                    dependency: {'element': 'link', 'not_empty': {}},
                },
            ],
            show_settings_on_create: true,
            render: function($, p, fp) {
                
                if (this.attrs['link'] == '') {
                    this.dom_element = $('<div class="az-element az-icon ' + this.attrs['el_class'] + '"><span class="' + this.attrs['icon'] + '" style="' + this.attrs['style'] + '"></span></div>');
                } else {
                    this.dom_element = $('<a href="' + this.attrs['link'] + '" class="az-element az-icon ' + this.attrs['el_class'] + '" target="' + this.attrs['link_target'] + '"><span class="' + this.attrs['icon'] + '" style="' + this.attrs['style'] + '"></span></a>');
                }
                $(this.dom_element).css('font-size', this.attrs['size'] + 'px');
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_image',
            name: t('Image'),
            icon: 'fa fa-picture-o',
            description: t('Single image'),
            params: [
                {
                    type: 'image',
                    heading: t('Image'),
                    param_name: 'image',
                    description: t('Select image from media library.'),
                },
                {
                    type: 'textfield',
                    heading: t('Image width'),
                    param_name: 'width',
                    description: t('You can use px, em, %, etc. or enter just number and it will use pixels.'),
                    can_be_empty: true,
                    value: '100%',
                },
                {
                    type: 'textfield',
                    heading: t('Image height'),
                    description: t('You can use px, em, %, etc. or enter just number and it will use pixels.'),
                    can_be_empty: true,
                    param_name: 'height',
                },
                {
                    type: 'checkbox',
                    heading: t('Link to large image?'),
                    param_name: 'img_link_large',
                    description: t('If selected, image will be linked to the larger image.'),
                    value: {
                        'yes': t("Yes, please"),
                    },
                },
                {
                    type: 'link',
                    heading: t('Image link'),
                    param_name: 'link',
                    description: t('Enter URL if you want this image to have a link.'),
                    dependency: {'element': 'img_link_large', 'is_empty': {}},
                },
                {
                    type: 'dropdown',
                    heading: t('Image link target'),
                    param_name: 'link_target',
                    description: t('Select where to open link.'),
                    value: target_options,
                    dependency: {'element': 'link', 'not_empty': {}},
                },
                {
                    type: 'dropdown',
                    heading: t('Start effect'),
                    param_name: 'adipoli_start',
                    tab: t('Adipoli'),
                    value: {
                        none: t('None'),
                        transparent: t('Transparent'),
                        normal: t('Normal'),
                        overlay: t('Overlay'),
                        grayscale: t('Grayscale'),
                    },
                },
                {
                    type: 'dropdown',
                    heading: t('Hover effect'),
                    param_name: 'adipoli_hover',
                    tab: t('Adipoli'),
                    value: {
                        none: t('None'),
                        normal: t('normal'),
                        popout: t('popout'),
                        sliceDown: t('sliceDown'),
                        sliceDownLeft: t('sliceDownLeft'),
                        sliceUp: t('sliceUp'),
                        sliceUpLeft: t('sliceUpLeft'),
                        sliceUpRandom: t('sliceUpRandom'),
                        sliceUpDown: t('sliceUpDown'),
                        sliceUpDownLeft: t('sliceUpDownLeft'),
                        fold: t('fold'),
                        foldLeft: t('foldLeft'),
                        boxRandom: t('boxRandom'),
                        boxRain: t('boxRain'),
                        boxRainReverse: t('boxRainReverse'),
                        boxRainGrow: t('boxRainGrow'),
                        boxRainGrowReverse: t('boxRainGrowReverse'),
                    },
                },
                {
                    type: 'checkbox',
                    heading: t('Splits?'),
                    param_name: 'splits',
                    tab: t('Splits'),
                    value: {
                        'yes': t("Yes, please"),
                    },
                },
                {
                    type: 'integer_slider',
                    heading: t('Number of Splits'),
                    param_name: 'splits_number',
                    tab: t('Splits'),
                    description: t('The number of strips you want to split the image into.'),
                    max: '20',
                    value: '8',
                    dependency: {'element': 'splits', 'not_empty': {}},
                },
                {
                    type: 'integer_slider',
                    heading: t('Space between Splits'),
                    param_name: 'splits_space',
                    tab: t('Splits'),
                    description: t('The horizontal gutter between each strip.'),
                    max: '50',
                    value: '5',
                    dependency: {'element': 'splits', 'not_empty': {}},
                },
                {
                    type: 'integer_slider',
                    heading: t('Offset for Splits'),
                    param_name: 'splits_offset',
                    tab: t('Splits'),
                    description: t('The vertical gutter overlayed at the top and bottom of alternate strips.'),
                    max: '100',
                    value: '10',
                    dependency: {'element': 'splits', 'not_empty': {}},
                },
                {
                    type: 'colorpicker',
                    heading: t('Background color'),
                    param_name: 'splits_bgcolor',
                    tab: t('Splits'),
                    description: t('The background colour of the vertical overlays (defaults to white).'),
                    value: '#ffffff',
                    dependency: {'element': 'splits', 'not_empty': {}},
                },
            ],
            frontend_render: true,
            show_settings_on_create: true,
            showed: function($, p, fp) {
                
                this.baseclass.prototype.showed.apply(this, arguments);
                var element = this;
                var prettyphoto_config = {
                    animationSpeed: 'normal', /* fast/slow/normal */
                    padding: 15, /* padding for each side of the picture */
                    opacity: 0.7, /* Value betwee 0 and 1 */
                    showTitle: true, /* true/false */
                    allowresize: true, /* true/false */
                    counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
                    //theme: 'light_square', /* light_rounded / dark_rounded / light_square / dark_square */
                    hideflash: false, /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
                    deeplinking: false, /* Allow prettyPhoto to update the url to enable deeplinking. */
                    modal: false, /* If set to true, only the close button will close the window */
                    social_tools: ''
                };

                if (this.attrs['img_link_large'] == 'yes') {
                    this.add_css('prettyphoto/css/prettyPhoto.css', 'prettyPhoto' in $.fn, function() {
                    });
                    this.add_js({
                        path: 'prettyphoto/js/jquery.prettyPhoto.js',
                        loaded: 'prettyPhoto' in $.fn,
                        callback: function() {
                            $(element.dom_element).find('a').prettyPhoto(prettyphoto_config);
                        }});
                }
                var adipoli = {};
                if (element.attrs['adipoli_start'] != '' && element.attrs['adipoli_start'] != 'none') {
                    adipoli['startEffect'] = element.attrs['adipoli_start'];
                }
                if (element.attrs['adipoli_hover'] != '' && element.attrs['adipoli_hover'] != 'none') {
                    adipoli['hoverEffect'] = element.attrs['adipoli_hover'];
                }
                if (Object.keys(adipoli).length > 0) {
                    this.add_css('adipoli/adipoli.css', 'adipoli' in $.fn, function() {
                    });
                    this.add_js_list({
                        paths: ['adipoli/jquery.adipoli.min.js', 'jquery-waypoints/waypoints.min.js'],
                        loaded: 'waypoint' in $.fn && 'adipoli' in $.fn,
                        callback: function() {
                            $(element.dom_element).waypoint(function(direction) {
                                $(element.dom_element).find('img').adipoli(adipoli);
                            }, {offset: '100%', triggerOnce: true});
                            $(document).trigger('scroll');
                        }});
                }
                if (element.attrs['splits'] == 'yes') {
                    this.add_js_list({
                        paths: ['picstrips/jquery.picstrips.min.js', 'jquery-waypoints/waypoints.min.js'],
                        loaded: 'waypoint' in $.fn && 'picstrips' in $.fn,
                        callback: function() {
                            $(element.dom_element).waypoint(function(direction) {
                                $(element.dom_element).find('img').picstrips({
                                    splits: Math.round(element.attrs['splits_number']),
                                    hgutter: Math.round(element.attrs['splits_space']) + 'px',
                                    vgutter: Math.round(element.attrs['splits_offset']) + 'px',
                                    bgcolor: element.attrs['splits_bgcolor']
                                });
                            }, {offset: '100%', triggerOnce: true});
                            $(document).trigger('scroll');
                        }});
                }
            },
            render: function($, p, fp) {
                
                var id = this.id;
                var element = this;
                this.dom_element = $('<div class="az-element az-image ' + this.attrs['el_class'] + '"></div>');
                function render_image(value, width, height) {
                    if ($.isNumeric(width))
                        width = width + 'px';
                    if ($.isNumeric(height))
                        height = height + 'px';
                    var img = $('<img src="' + value + '" alt="">');
                    if (width.length > 0)
                        $(img).attr('width', width);
                    if (height.length > 0)
                        $(img).attr('height', height);
                    return img;
                }
                var img = render_image(this.attrs['image'], this.attrs['width'], this.attrs['height']);
                $(img).appendTo(this.dom_element);

                $(this.dom_element).find('img').attr('style', this.attrs['style']);

                if (this.attrs['img_link_large'] == 'yes') {
                    $(this.dom_element).find('img').each(function() {
                        $(this).wrap('<a href="' + $(this).attr('src') + '" data-rel="prettyPhoto[rel-' + id + ']"></a>');
                    });
                } else {
                    if (this.attrs['link'] != '') {
                        $(this.dom_element).find('img').each(function() {
                            $(this).wrap('<a href="' + element.attrs['link'] + '" target="' + element.attrs['link_target'] + '"></a>');
                        });
                    }
                }
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_button',
            name: t('Button'),
            icon: 'fa fa-link',
            description: t('Button'),
            params: [
                {
                    type: 'textfield',
                    heading: t('Title'),
                    param_name: 'title',
                    description: t('Text on the button'),
                },
                {
                    type: 'link',
                    heading: t('Link'),
                    param_name: 'link',
                    description: t('Button link (url).'),
                },
                {
                    type: 'dropdown',
                    heading: t('Link target'),
                    param_name: 'link_target',
                    description: t('Select where to open link.'),
                    value: target_options,
                    dependency: {'element': 'link', 'not_empty': {}},
                },
                {
                    type: 'dropdown',
                    heading: t('Type'),
                    param_name: 'type',
                    value: _.object([p + 'btn-default', p + 'btn-primary', p + 'btn-success', p + 'btn-info', p + 'btn-warning', p + 'btn-danger'], [t('Default'), t('Primary'), t('Success'), t('Info'), t('Warning'), t('Danger')]),
                },
                {
                    type: 'dropdown',
                    heading: t('Size'),
                    param_name: 'size',
                    value: _.object(['', p + 'btn-lg', p + 'btn-sm', p + 'btn-xs'], [t('Normal'), t('Large'), t('Small'), t('Extra small')]),
                },
            ],
            show_settings_on_create: true,
            render: function($, p, fp) {
                
                
                if (this.attrs['link'] == '') {
                    this.dom_element = $('<div class="az-element az-button ' + this.attrs['el_class'] + '"><button type="button" class="' + p + 'btn ' + this.attrs['type'] + ' ' + this.attrs['size'] + '" style="' + this.attrs['style'] + '">' + this.attrs['title'] + '</button></div>');
                } else {
                    this.dom_element = $('<div class="az-element az-button ' + this.attrs['el_class'] + '"><a href="' + this.attrs['link'] + '" type="button" class="' + p + 'btn ' + this.attrs['type'] + ' ' + this.attrs['size'] + '" style="' + this.attrs['style'] + '" target="' + this.attrs['link_target'] + '">' + this.attrs['title'] + '</a></div>');
                }
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_jumbotron',
            name: t('Jumbotron'),
            icon: 'fa fa-exclamation-circle',
            description: t('Showcase key content'),
            params: [
            ],
            is_container: true,
            get_empty: function() {
                return '<div class="az-empty"><div class="top-left ' + p + 'well"><h1>↖</h1>' + t('Settings for this jumbotron.') + '</div></div>';
            },
            render: function($, p, fp) {
                
                
                this.dom_element = $('<div class="az-element az-ctnr az-jumbotron ' + p + 'jumbotron ' + this.attrs['el_class'] + '" style="' + this.attrs['style'] + '"></div>');
                this.dom_content_element = this.dom_element;
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_panel',
            name: t('Panel'),
            icon: 'fa fa-square-o',
            description: t('Box for your content'),
            params: [
                {
                    type: 'textfield',
                    heading: t('Title'),
                    param_name: 'title',
                },
                {
                    type: 'dropdown',
                    heading: t('Type'),
                    param_name: 'type',
                    value: _.object([p + 'panel-default', p + 'panel-primary', p + 'panel-success', p + 'panel-info', p + 'panel-warning', p + 'panel-danger'], [t('Default'), t('Primary'), t('Success'), t('Info'), t('Warning'), t('Danger'), ])
                },
            ],
            is_container: true,
            show_settings_on_create: true,
            get_empty: function() {
                return '<div class="az-empty"><div class="top-left ' + p + 'well"><h1>↖</h1>' + t('Settings for this panel. You can enter panel title by click on this button: ') + '<span class="' + p + 'glyphicon ' + p + 'glyphicon-pencil"></span></div></div>';
            },
            render: function($, p, fp) {
                
                
                this.dom_element = $('<div class="az-element az-panel ' + p + 'panel ' + this.attrs['el_class'] + ' ' + this.attrs['type'] + '" style="' + this.attrs['style'] + '"></div>');
                if (this.attrs['title'] != '') {
                    var heading = $('<div class="' + p + 'panel-heading"><h3 class="' + p + 'panel-title">' + this.attrs['title'] + '</div></div>');
                    $(this.dom_element).append(heading);
                }
                var body = $('<div class="' + p + 'panel-body az-ctnr"></div>').appendTo(this.dom_element);
                this.dom_content_element = body;
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_popup',
            name: t('Popup'),
            icon: 'fa fa-paper-plane',
            description: t('Popup container. Can contain any number of any types of elements.'),
            category: t('Layout'),
            params: [
                {
                    type: 'dropdown',
                    heading: t('Popup opener type'),
                    param_name: 'type',
                    description: t('Select which element will open popup.'),
                    value: {
                        'link': t('Text link'),
                        'button': t('Button'),
                        'image': t('Image'),
                        'icon': t('Icon'),
                    },
                },
                {
                    type: 'integer_slider',
                    heading: t('Hiding pause popup'),
                    param_name: 'hiding_pause',
                    description: t('Pause before popup is hiding.'),
                    max: '3000',
                    value: '0',
                },
                {
                    type: 'textfield',
                    heading: t('Text'),
                    param_name: 'text',
                    value: t('Open popup'),
                    dependency: {'element': 'type', 'value': ['link', 'button']},
                },
                {
                    type: 'dropdown',
                    heading: t('Type'),
                    param_name: 'button_type',
                    value: _.object([p + 'btn-default', p + 'btn-primary', p + 'btn-success', p + 'btn-info', p + 'btn-warning', p + 'btn-danger'], [t('Default'), t('Primary'), t('Success'), t('Info'), t('Warning'), t('Danger')]),
                    dependency: {'element': 'type', 'value': ['button']},
                },
                {
                    type: 'dropdown',
                    heading: t('Size'),
                    param_name: 'size',
                    value: _.object(['', p + 'btn-lg', p + 'btn-sm', p + 'btn-xs'], [t('Normal'), t('Large'), t('Small'), t('Extra small')]),
                    dependency: {'element': 'type', 'value': ['button']},
                },
                {
                    type: 'image',
                    heading: t('Image'),
                    param_name: 'image',
                    dependency: {'element': 'type', 'value': ['image']},
                },
                {
                    type: 'textfield',
                    heading: t('Image width'),
                    param_name: 'width',
                    description: t('You can use px, em, %, etc. or enter just number and it will use pixels.'),
                    can_be_empty: true,
                    value: '100%',
                    dependency: {'element': 'type', 'value': ['image']},
                },
                {
                    type: 'textfield',
                    heading: t('Image height'),
                    description: t('You can use px, em, %, etc. or enter just number and it will use pixels.'),
                    can_be_empty: true,
                    param_name: 'height',
                    dependency: {'element': 'type', 'value': ['image']},
                },
                {
                    type: 'icon',
                    heading: t('Icon'),
                    param_name: 'icon',
                    dependency: {'element': 'type', 'value': ['icon']},
                },
                {
                    type: 'integer_slider',
                    heading: t('Icon size'),
                    param_name: 'size',
                    dependency: {'element': 'type', 'value': ['icon']},
                },
            ],
            is_container: true,
            disallowed_elements: ['az_popup'],
            get_button: function() {
                return '<div class="' + p + 'well ' + p + 'text-center ' + p + 'text-overflow" data-az-element="' + this.base + '" style="width:100%;"><i class="' + p + 'text-primary ' + this.icon + '"></i><div>' + this.name + '</div><div class="' + p + 'text-muted ' + p + 'small">' + this.description + '</div></div>';
            },
            get_empty: function() {
                return '<div class="az-empty"><div class="bottom ' + p + 'well"><h1>' + t('At the moment popup is empty. Click to put an element here.') + '</h1></div></div>';
            },
            showed: function($, p, fp) {
                
                
                var element = this;
                function open_popup() {
                    $(element.dom_content_element).removeClass(p + 'hidden');
                    for (var i = 0; i < element.children.length; i++) {
                        if ('trigger_start_in_animation' in element.children[i]) {
                            element.children[i].trigger_start_in_animation();
                        }
                    }

                    $('<div class="' + p + 'modal-backdrop ' + p + 'in"></div>').appendTo(element.dom_element).click(function() {
                        var backdrop = this;
                        setTimeout(function() {
                            $(backdrop).remove();
                            $(element.dom_content_element).addClass(p + 'hidden');
                        }, element.attrs['hiding_pause']);
                        for (var i = 0; i < element.children.length; i++) {
                            if ('trigger_start_out_animation' in element.children[i]) {
                                element.children[i].trigger_start_out_animation();
                            }
                        }
                    });
                    return false;
                }
                $(this.dom_element).find('.open-popup').click(open_popup);
            },
            render: function($, p, fp) {
                
                
                this.dom_element = $('<div class="az-element az-popup ' + this.attrs['el_class'] + '" style="' + this.attrs['style'] + '"></div>');
                switch (this.attrs['type']) {
                    case '':
                    case 'link':
                        $('<a class="open-popup" href="#">' + this.attrs['text'] + '</a>').appendTo(this.dom_element);
                        break;
                    case 'button':
                        $('<button type="button" class="open-popup ' + p + 'btn ' + this.attrs['button_type'] + ' ' + this.attrs['size'] + '">' + this.attrs['text'] + '</button>').appendTo(this.dom_element);
                        break;
                    case 'image':
                        function render_image(value, width, height) {
                            if ($.isNumeric(width))
                                width = width + 'px';
                            if ($.isNumeric(height))
                                height = height + 'px';
                            var img = $('<img src="' + value + '" alt="">');
                            if (width.length > 0)
                                $(img).attr('width', width);
                            if (height.length > 0)
                                $(img).attr('height', height);
                            return img;
                        }
                        var img = render_image(this.attrs['image'], this.attrs['width'], this.attrs['height']);
                        $(img).appendTo(this.dom_element);
                        $(img).addClass('open-popup');
                        break;
                    case 'icon':
                        $('<span class="open-popup ' + this.attrs['icon'] + '"></span>').appendTo(this.dom_element).css('font-size', this.attrs['size'] + 'px');
                        break;
                    default:
                        break;
                }
                this.dom_content_element = $('<div class="' + p + 'container az-popup-ctnr az-ctnr ' + p + 'hidden"></div>').appendTo(this.dom_element);
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_alert',
            name: t('Alert'),
            icon: 'fa fa-info-circle',
            description: t('Alert box'),
            params: [
                {
                    type: 'textfield',
                    heading: t('Message'),
                    param_name: 'message',
                    value: t('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'),
                },
                {
                    type: 'dropdown',
                    heading: t('Type'),
                    param_name: 'type',
                    description: t('Select message type.'),
                    value: _.object([p + 'alert-success', p + 'alert-info', p + 'alert-warning', p + 'alert-danger'], [t('Success'), t('Info'), t('Warning'), t('Danger')]),
                },
            ],
            show_settings_on_create: true,
            render: function($, p, fp) {
                
                this.dom_element = $('<div class="az-element az-alert ' + p + 'alert ' + this.attrs['type'] + ' ' + this.attrs['el_class'] + '" style="' + this.attrs['style'] + '">' + this.attrs['message'] + '</div>');
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_separator',
            name: t('Separator'),
            icon: 'fa fa-sort',
            description: t('Horizontal separator line'),
            params: [
            ],
            render: function($, p, fp) {
                this.dom_element = $('<hr class="az-element az-separator ' + this.attrs['el_class'] + '" style="' + this.attrs['style'] + '"></hr>');
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_blockquote',
            name: t('Blockquote'),
            icon: 'fa fa-comment',
            description: t('Blockquote box'),
            params: [
                {
                    type: 'textarea',
                    heading: t('Text'),
                    param_name: 'content',
                    value: t('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
                },
                {
                    type: 'textfield',
                    heading: t('Cite'),
                    param_name: 'cite',
                },
                {
                    type: 'checkbox',
                    heading: t('Reverse?'),
                    param_name: 'reverse',
                    value: {
                        'blockquote-reverse': t("Yes, please"),
                    },
                },
            ],
            show_settings_on_create: true,
            is_container: true,
            has_content: true,
            render: function($, p, fp) {
                
                this.dom_element = $('<blockquote class="az-element az-blockquote ' + this.attrs['el_class'] + ' ' + this.attrs['reverse'] + '" style="' + this.attrs['style'] + '">' + this.attrs['content'] + '</blockquote>');
                this.dom_content_element = this.dom_element;
                if (this.attrs['cite'] != '')
                    $(this.dom_element).append('<footer><cite>' + this.attrs['cite'] + '</cite></footer>');
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_progress_bar',
            name: t('ProgressBar'),
            icon: 'fa fa-align-left',
            description: t('Animated progress bar'),
            params: [
                {
                    type: 'textfield',
                    heading: t('Label'),
                    param_name: 'label',
                },
                {
                    type: 'integer_slider',
                    heading: t('Width'),
                    param_name: 'width',
                    value: '50',
                },
                {
                    type: 'dropdown',
                    heading: t('Type'),
                    param_name: 'type',
                    value: _.object(['', p + 'progress-bar-success', p + 'progress-bar-info', p + 'progress-bar-warning', p + 'progress-bar-danger'], [t('Default'), t('Success'), t('Info'), t('Warning'), t('Danger')]),
                },
                {
                    type: 'checkbox',
                    heading: t('Options'),
                    param_name: 'options',
                    value: {
                        'progress-striped': t("Add Stripes?"),
                        'active': t("Add animation? Will be visible with striped bars."),
                    },
                },
            ],
            render: function($, p, fp) {
                
                
                this.dom_element = $('<div class="az-element az-progress-bar ' + p + 'progress ' + this.attrs['el_class'] + ' ' + this.attrs['options'].replace(',', ' ') + '" style="' + this.attrs['style'] + '"><div class="' + p + 'progress-bar ' + this.attrs['type'] + '" role="progressbar" aria-valuenow="' + this.attrs['width'] + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + this.attrs['width'] + '%">' + this.attrs['label'] + '</div></div>');
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_gallery',
            name: t('Gallery'),
            icon: 'fa fa-flickr',
            description: t('Responsive image gallery'),
            params: [
                {
                    type: 'images',
                    heading: t('Images'),
                    param_name: 'images',
                    description: t('Select images from media library.'),
                },
                {
                    type: 'dropdown',
                    heading: t('Type'),
                    param_name: 'type',
                    description: t('Select gallery type.'),
                    value: {
                        'image_grid': t('Image grid'),
                        'flexslider_slide': t('Flex slider slide'),
                        'flexslider_fade': t('Flex slider fade'),
                        'nivoslider': t('Nivo slider'),
                    },
                },
                {
                    type: 'checkbox',
                    heading: t('Masonry grid?'),
                    param_name: 'masonry',
                    value: {
                        'yes': t("Yes, please"),
                    },
                    dependency: {'element': 'type', 'value': ['image_grid']},
                },
                {
                    type: 'integer_slider',
                    heading: t('Masonry column width'),
                    param_name: 'masonry_width',
                    description: t('In pixels.'),
                    max: '500',
                    value: '200',
                    dependency: {'element': 'masonry', 'value': ['yes']},
                },
                {
                    type: 'integer_slider',
                    heading: t('Image width'),
                    param_name: 'width',
                    can_be_empty: true,
                    max: '1000',
                    value: '100',
                },
                {
                    type: 'integer_slider',
                    heading: t('Image height'),
                    param_name: 'height',
                    can_be_empty: true,
                    max: '1000',
                    value: '100',
                },
                {
                    type: 'integer_slider',
                    heading: t('Auto rotate slides'),
                    param_name: 'interval',
                    description: t('Auto rotate slides each X seconds.'),
                    max: '20',
                    value: '3',
                },
                {
                    type: 'dropdown',
                    heading: t('On click'),
                    param_name: 'onclick',
                    description: t('Define action for onclick event if needed.'),
                    value: {
                        'link_no': t('Do nothing'),
                        'link_image': t('Open PrettyPhoto'),
                        'custom_link': t('Open custom link'),
                    },
                },
                {
                    type: 'links',
                    heading: t('Custom links'),
                    param_name: 'custom_links',
                    description: t('Enter links for each slide here. Divide links with linebreaks (Enter).'),
                    dependency: {'element': 'onclick', 'value': ['custom_link']},
                },
                {
                    type: 'dropdown',
                    heading: t('Custom link target'),
                    param_name: 'custom_links_target',
                    description: t('Select where to open custom links.'),
                    value: target_options,
                    dependency: {'element': 'onclick', 'value': ['custom_link']},
                },
            ],
            frontend_render: true,
            show_settings_on_create: true,
            showed: function($, p, fp) {
                
                this.baseclass.prototype.showed.apply(this, arguments);
                var element = this;
                var sliderTimeout = this.attrs['interval'] * 1000;
                var SliderBuilderObjectpeed = 800;
                switch (this.attrs['type']) {
                    case 'flexslider_slide':
                    case 'flexslider_fade':
                        this.add_css('flexslider/flexslider.css', 'flexslider' in $.fn, function() {
                        });
                        this.add_js({
                            path: 'flexslider/jquery.flexslider-min.js',
                            loaded: 'flexslider' in $.fn,
                            callback: function() {
                                var slideshow = true;
                                if (sliderTimeout == 0)
                                    slideshow = false;
                                $(element.dom_element).find('ul').addClass('slides');
                                $(element.dom_element).flexslider({
                                    selector: 'ul > li',
                                    animation: element.attrs['type'].split('_')[1],
                                    slideshow: slideshow,
                                    slideshowSpeed: sliderTimeout,
                                    SliderBuilderObjectpeed: SliderBuilderObjectpeed,
                                    smoothHeight: true
                                });
                            }});
                        break;
                    case 'nivoslider':
                        this.add_css('nivoslider/nivo-slider.css', 'nivoSlider' in $.fn, function() {
                        });
                        this.add_css('nivoslider/themes/default/default.css', 'nivoSlider' in $.fn, function() {
                        });
                        this.add_js({
                            path: 'nivoslider/jquery.nivo.slider.pack.js',
                            loaded: 'nivoSlider' in $.fn,
                            callback: function() {
                                $(element.dom_element).addClass('theme-default');
                                $(element.dom_element).find('ul').nivoSlider({
                                    effect: 'boxRainGrow,boxRain,boxRainReverse,boxRainGrowReverse', // Specify sets like: 'fold,fade,sliceDown'
                                    slices: 15, // For slice animations
                                    boxCols: 8, // For box animations
                                    boxRows: 4, // For box animations
                                    animSpeed: SliderBuilderObjectpeed, // Slide transition speed
                                    pauseTime: sliderTimeout, // How long each slide will show
                                    startSlide: 0, // Set starting Slide (0 index)
                                    directionNav: true, // Next & Prev navigation
                                    directionNavHide: true, // Only show on hover
                                    controlNav: true, // 1,2,3... navigation
                                    keyboardNav: false, // Use left & right arrows
                                    pauseOnHover: true, // Stop animation while hovering
                                    manualAdvance: false, // Force manual transitions
                                    prevText: 'Prev', // Prev directionNav text
                                    nextText: 'Next' // Next directionNav text
                                });
                            }});
                        break;
                    case 'image_grid':
                        this.add_js({
                            path: 'js/masonry.min.js',
                            loaded: 'masonry' in $.fn,
                            callback: function() {
                                if (element.attrs['masonry'] == 'yes') {
                                    $(element.dom_element).find('ul').masonry({
                                        itemSelector: 'li',
                                        columnWidth: parseInt(element.attrs['masonry_width']),
                                    });
                                } else {
                                    $(element.dom_element).find('ul').masonry({
                                        itemSelector: 'li',
                                    });
                                }
                            }});
                        break;
                    default:
                        break;
                }
                var prettyphoto_config = {
                    animationSpeed: 'normal', /* fast/slow/normal */
                    padding: 15, /* padding for each side of the picture */
                    opacity: 0.7, /* Value betwee 0 and 1 */
                    showTitle: true, /* true/false */
                    allowresize: true, /* true/false */
                    counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
                    //theme: 'light_square', /* light_rounded / dark_rounded / light_square / dark_square */
                    hideflash: false, /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
                    deeplinking: false, /* Allow prettyPhoto to update the url to enable deeplinking. */
                    modal: false, /* If set to true, only the close button will close the window */
                    social_tools: ''
                };
                switch (this.attrs['onclick']) {
                    case 'link_image':
                        this.add_css('prettyphoto/css/prettyPhoto.css', 'prettyPhoto' in $.fn, function() {
                        });
                        this.add_js({
                            path: 'prettyphoto/js/jquery.prettyPhoto.js',
                            loaded: 'prettyPhoto' in $.fn,
                            callback: function() {
                                $(element.dom_element).find('> ul > li > a').prettyPhoto(prettyphoto_config);
                            }
                        });
                        break;
                    default:
                        break;
                }
            },
            render: function($, p, fp) {
                
                var id = this.id;
                var element = this;
                var images = this.attrs['images'].split(',');
                this.dom_element = $('<div id="' + this.id + '" class="az-element az-gallery ' + this.attrs['el_class'] + '" style="' + this.attrs['style'] + '"></div>');
                var ul = $('<ul></ul>').appendTo(this.dom_element);
                function render_image(value, width, height) {
                    if ($.isNumeric(width))
                        width = width + 'px';
                    if ($.isNumeric(height))
                        height = height + 'px';
                    var img = $('<img src="' + value + '" alt="">');
                    if (width.length > 0)
                        $(img).attr('width', width);
                    if (height.length > 0)
                        $(img).attr('height', height);
                    return img;
                }
                for (var i = 0; i < images.length; i++) {
                    var img = render_image(images[i], this.attrs['width'], this.attrs['height']);
                    $('<li></li>').appendTo(ul).append(img);
                }

                switch (this.attrs['onclick']) {
                    case 'link_image':
                        $(this.dom_element).find('ul > li > *').each(function() {
                            $(this).wrap('<a href="' + $(this).attr('src') + '" data-rel="prettyPhoto[rel-' + id + ']"></a>');
                        });
                        break;
                    case 'custom_link':
                        var links = this.attrs['custom_links'].split("\n");
                        var i = 0;
                        $(this.dom_element).find('ul > li > *').each(function() {
                            $(this).wrap('<a href="' + links[i] + '" target="' + element.attrs['custom_links_target'] + '"></a>');
                            i++;
                        });
                        break;
                    default:
                        break;
                }
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_images_carousel',
            name: t('Images carousel'),
            icon: 'fa fa-refresh',
            description: t('Animated carousel with images'),
            params: [
                {
                    type: 'images',
                    heading: t('Images'),
                    param_name: 'images',
                },
                {
                    type: 'textfield',
                    heading: t('Image width'),
                    param_name: 'width',
                    description: t('You can use px, em, %, etc. or enter just number and it will use pixels.'),
                    can_be_empty: true,
                    value: '100%',
                },
                {
                    type: 'integer_slider',
                    heading: t('Image height'),
                    param_name: 'height',
                    can_be_empty: true,
                    max: '1000',
                    value: '100',
                },
                {
                    type: 'integer_slider',
                    heading: t('Interval'),
                    param_name: 'interval',
                    max: '10000',
                    value: '5000',
                },
                {
                    type: 'dropdown',
                    heading: t('On click'),
                    param_name: 'onclick',
                    description: t('Define action for onclick event if needed.'),
                    value: {
                        'link_no': t('Do nothing'),
                        'link_image': t('Open PrettyPhoto'),
                        'custom_link': t('Open custom link'),
                    },
                },
                {
                    type: 'links',
                    heading: t('Custom links'),
                    param_name: 'custom_links',
                    description: t('Enter links for each slide here. Divide links with linebreaks (Enter).'),
                    dependency: {'element': 'onclick', 'value': ['custom_link']},
                },
                {
                    type: 'dropdown',
                    heading: t('Custom link target'),
                    param_name: 'custom_links_target',
                    description: t('Select where to open custom links.'),
                    value: target_options,
                    dependency: {'element': 'onclick', 'value': ['custom_link']},
                },
                {
                    type: 'checkbox',
                    heading: t('Hide'),
                    param_name: 'hide',
                    value: {
                        'pagination_control': t("Hide pagination control"),
                        'prev_next_buttons': t("Hide prev/next buttons"),
                    },
                },
            ],
            show_settings_on_create: true,
            showed: function($, p, fp) {
                
                
                this.baseclass.prototype.showed.apply(this, arguments);
                var element = this;
                var prettyphoto_config = {
                    animationSpeed: 'normal', /* fast/slow/normal */
                    padding: 15, /* padding for each side of the picture */
                    opacity: 0.7, /* Value betwee 0 and 1 */
                    showTitle: true, /* true/false */
                    allowresize: true, /* true/false */
                    counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
                    //theme: 'light_square', /* light_rounded / dark_rounded / light_square / dark_square */
                    hideflash: false, /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
                    deeplinking: false, /* Allow prettyPhoto to update the url to enable deeplinking. */
                    modal: false, /* If set to true, only the close button will close the window */
                    social_tools: ''
                };
                switch (this.attrs['onclick']) {
                    case 'link_image':
                        this.add_css('prettyphoto/css/prettyPhoto.css', 'prettyPhoto' in $.fn, function() {
                        });
                        this.add_js({
                            path: 'prettyphoto/js/jquery.prettyPhoto.js',
                            loaded: 'prettyPhoto' in $.fn,
                            callback: function() {
                                $(element.dom_element).find('.' + p + 'carousel-inner .' + p + 'item a').prettyPhoto(prettyphoto_config);
                            }
                        });
                        break;
                    default:
                        break;
                }
                $(this.dom_element)[fp + 'carousel']({
                    interval: this.attrs['interval'],
                    pause: 'hover',
                });
            },
            render: function($, p, fp) {
                
                
                var id = this.id;
                var element = this;
                var images = this.attrs['images'].split(',');

                this.dom_element = $('<div id="' + this.id + '" class="az-element az-images-carousel ' + p + 'carousel ' + p + 'slide ' + this.attrs['el_class'] + '" data-ride="carousel" style="' + this.attrs['style'] + '"></div>');

                var hide = this.attrs['hide'].split(',');

                if ($.inArray('pagination_control', hide) < 0) {
                    var indicators = $('<ol class="' + p + 'carousel-indicators"></ol>');
                    for (var i = 0; i < images.length; i++) {
                        $(indicators).append('<li data-target="#' + this.id + '" data-slide-to="' + i + '"></li>');
                    }
                    $(this.dom_element).append(indicators);
                }

                var inner = $('<div class="' + p + 'carousel-inner"></div>');
                for (var i = 0; i < images.length; i++) {
                    var item = $('<div class="' + p + 'item"></div>').appendTo(inner);
                    $(item).css('background-image', 'url(' + images[i] + ')');
                    $(item).css('background-position', 'center');
                    $(item).css('background-repeat', 'no-repeat');
                    $(item).css('background-size', 'cover');
                    $(item).width(this.attrs['width']);
                    $(item).height(this.attrs['height'] + 'px');
                }
                $(this.dom_element).append(inner);

                if ($.inArray('prev_next_buttons', hide) < 0) {
                    var controls = $('<a class="' + p + 'left ' + p + 'carousel-control" href="#' + this.id + '" data-slide="prev"><span class="' + p + 'glyphicon ' + p + 'glyphicon-chevron-left"></span></a><a class="' + p + 'right ' + p + 'carousel-control" href="#' + this.id + '" data-slide="next"><span class="' + p + 'glyphicon ' + p + 'glyphicon-chevron-right"></span></a>');
                    $(this.dom_element).append(controls);
                }

                $(this.dom_element).find('.' + p + 'carousel-indicators li:first').addClass(p + 'active');
                $(this.dom_element).find('.' + p + 'carousel-inner .' + p + 'item:first').addClass(p + 'active');


                switch (this.attrs['onclick']) {
                    case 'link_image':
                        $(this.dom_element).find('.' + p + 'carousel-inner .' + p + 'item img').each(function() {
                            $(this).wrap('<a href="' + $(this).attr('src') + '" data-rel="prettyPhoto[rel-' + id + ']"></a>');
                        });
                        break;
                    case 'custom_link':
                        var links = this.attrs['custom_links'].split("\n");
                        var i = 0;
                        $(this.dom_element).find('.' + p + 'carousel-inner .' + p + 'item img').each(function() {
                            $(this).wrap('<a href="' + links[i] + '" target="' + element.attrs['custom_links_target'] + '"></a>');
                            i++;
                        });
                        break;
                    default:
                        break;
                }
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_video',
            name: t('Video'),
            icon: 'fa fa-film',
            description: t('Embed YouTube / Vimeo player'),
            params: [
                {
                    type: 'textfield',
                    heading: t('Video link'),
                    param_name: 'link',
                    description: t('Link to the video.'),
                },
                {
                    type: 'textfield',
                    heading: t('Video width'),
                    param_name: 'width',
                    description: t('You can use px, em, %, etc. or enter just number and it will use pixels.'),
                    value: '100%',
                },
                {
                    type: 'textfield',
                    heading: t('Video height'),
                    param_name: 'height',
                    description: t('You can use px, em, %, etc. or enter just number and it will use pixels.'),
                },
            ],
            show_settings_on_create: true,
            render: function($, p, fp) {
                
                function youtube_parser(url) {
                    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
                    var match = url.match(regExp);
                    if (match && match[7].length == 11) {
                        return match[7];
                    } else {
                        return false;
                    }
                }
                function vimeo_parser(url) {
                    var m = url.match(/^.+vimeo.com\/(.*\/)?([^#\?]*)/);
                    return m ? m[2] || m[1] : false;
                }
                var url = youtube_parser(this.attrs['link']);
                if (url) {
                    url = 'http://www.youtube.com/embed/' + url;
                } else {
                    url = vimeo_parser(this.attrs['link']);
                    if (url) {
                        url = 'http://player.vimeo.com/video/' + url;
                    } else {
                        url = '';
                    }
                }
                this.dom_element = $('<div class="az-element az-video ' + this.attrs['el_class'] + '" style="' + this.attrs['style'] + '"><iframe src="' + url + '" type="text/html" width="' + this.attrs['width'] + '" height="' + this.attrs['height'] + '" webkitallowfullscreen mozallowfullscreen allowfullscreen frameborder="0"></iframe></div>');
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_html',
            name: t('HTML'),
            icon: 'fa fa-file-code-o',
            description: t('Output raw html code on your page'),
            params: [
                {
                    type: 'html',
                    heading: t('Raw html'),
                    param_name: 'content',
                    description: t('Enter your HTML content.'),
                    value: t('<p>I am raw html block.<br/>Click edit button to change this html</p>'),
                },
            ],
            show_settings_on_create: true,
            is_container: true,
            has_content: true,
            render: function($, p, fp) {
                
                this.dom_element = $('<div class="az-element az-html ' + this.attrs['el_class'] + '" style="' + this.attrs['style'] + '">' + this.attrs['content'] + '</div>');
                this.dom_content_element = this.dom_element;
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_countdown',
            name: t('Countdown Timer'),
            icon: 'fa fa-clock-o',
            description: t('Place countdown element'),
            params: [
                {
                    type: 'dropdown',
                    heading: t('Countdown style'),
                    param_name: 'countdown_style',
                    description: t('Select the style for the countdown element.'),
                    value: {
                        'style1': t('Style 1'),
                        'style2': t('Style 2'),
                        'style3': t('Style 3'),
                        'style4': t('Style 4'),
                        'style5': t('Style 5'),
                        'style6': t('Style 6'),
                        'style7': t('Style 7'),
                        'style8': t('Style 8'),
                        'style9': t('Style 9'),
                        'style10': t('Style 10'),
                    },
                },
                {
                    type: 'dropdown',
                    heading: t('Date / Time Limitations'),
                    param_name: 'counter_scope',
                    description: t('Select the countdown scope in terms of date and time.'),
                    value: {
                        'date': t('Specify Date Only'),
                        'date_time': t('Specify Date and Time'),
                        'repeating': t('Specifiy Time Only (repeating on every day)'),
                        'resetting': t('Resetting Counter (set interval up to 24 hours)'),
                    },
                },
                {
                    type: 'datetime',
                    heading: t('Date'),
                    param_name: 'date',
                    datepicker: true,
                    description: t('Select the date to which you want to count down to.'),
                    formatDate: 'd.m.Y',
                    dependency: {'element': 'counter_scope', 'value': ['date']},
                },
                {
                    type: 'datetime',
                    heading: t('Date / Time'),
                    param_name: 'date_time',
                    timepicker: true,
                    datepicker: true,
                    description: t('Select the date and time to which you want to count down to.'),
                    formatDate: 'd.m.Y',
                    formatTime: 'H',
                    dependency: {'element': 'counter_scope', 'value': ['date_time']},
                },
                {
                    type: 'datetime',
                    heading: t('Time'),
                    param_name: 'time',
                    timepicker: true,
                    description: t('Select the time on the day above to which you want to count down to.'),
                    formatTime: 'H',
                    dependency: {'element': 'counter_scope', 'value': ['repeating']},
                },
                {
                    type: 'integer_slider',
                    heading: t('Reset in Hours'),
                    param_name: 'reset_hours',
                    max: 24,
                    description: t('Define the number of hours until countdown reset.'),
                    dependency: {'element': 'counter_scope', 'value': ['resetting']},
                },
                {
                    type: 'integer_slider',
                    heading: t('Reset in Minutes'),
                    param_name: 'reset_minutes',
                    max: 60,
                    description: t('Define the number of minutes until countdown reset.'),
                    dependency: {'element': 'counter_scope', 'value': ['resetting']},
                },
                {
                    type: 'integer_slider',
                    heading: t('Reset in Seconds'),
                    param_name: 'reset_seconds',
                    max: 60,
                    description: t('Define the number of seconds until countdown reset.'),
                    dependency: {'element': 'counter_scope', 'value': ['resetting']},
                },
                {
                    type: 'link',
                    heading: t('Page Referrer'),
                    param_name: 'referrer',
                    description: t('Provide an optional link to another site/page to be opened after countdown expires.'),
                    dependency: {'element': 'counter_scope', 'value': ['repeating', 'resetting']},
                },
                {
                    type: 'checkbox',
                    heading: t('Automatic Restart'),
                    param_name: 'restart',
                    description: t('Switch the toggle if you want to restart the countdown after each expiration.'),
                    value: {
                        'yes': t("Yes, please"),
                    },
                    dependency: {'element': 'counter_scope', 'value': ['resetting']},
                },
                {
                    type: 'saved_datetime',
                    param_name: 'saved',
                },
                {
                    type: 'checkbox',
                    heading: t('Display Options'),
                    param_name: 'display',
                    value: {
                        'days': t("Show Remaining Days"),
                        'hours': t("Show Remaining Hours"),
                        'minutes': t("Show Remaining Minutes"),
                        'seconds': t("Show Remaining Seconds"),
                    },
                },
            ],
            show_settings_on_create: true,
            frontend_render: true,
            showed: function($, p, fp) {
                
                this.baseclass.prototype.showed.apply(this, arguments);
                var element = this;
                this.add_css('counteverest/css/normalize.css', 'countEverest' in $.fn, function() {
                });
                this.add_css('css/counteverest-main.css', 'countEverest' in $.fn, function() {
                });
                this.add_js_list({
                    paths: ['counteverest/js/vendor/jquery.counteverest.min.js', 'datetimepicker/jquery.datetimepicker.js'],
                    loaded: 'countEverest' in $.fn && 'datetimepicker' in $.fn,
                    callback: function() {
                        var options = {};
                        switch (element.attrs['countdown_style']) {
                            case 'style2':
                                var $example = $(element.dom_element),
                                        $ceDays = $example.find('.ce-days'),
                                        $ceHours = $example.find('.ce-hours'),
                                        $ceMinutes = $example.find('.ce-minutes'),
                                        $ceSeconds = $example.find('.ce-seconds'),
                                        $daysFill = $example.find('.bar-days').find('.fill'),
                                        $hoursFill = $example.find('.bar-hours').find('.fill'),
                                        $minutesFill = $example.find('.bar-minutes').find('.fill'),
                                        $secondsFill = $example.find('.bar-seconds').find('.fill'),
                                        now = new Date(),
                                        then = new Date(now.getTime() + (14 * 24 * 60 * 60 * 1000));
                                function setBar($el, value, max) {
                                    var barWidth = 100 - (100 / max * value);
                                    $el.width(barWidth + '%');
                                }
                                options = {
                                    onChange: function() {
                                        setBar($daysFill, this.days, 365);
                                        setBar($hoursFill, this.hours, 24);
                                        setBar($minutesFill, this.minutes, 60);
                                        setBar($secondsFill, this.seconds, 60);
                                    }
                                }
                                break;
                            case 'style6':
                                function countEverestFlipAnimate($el, data) {
                                    $el.each(function(index) {
                                        var $this = $(this),
                                                $flipFront = $this.find('.ce-flip-front'),
                                                $flipBack = $this.find('.ce-flip-back'),
                                                field = $flipBack.text(),
                                                fieldOld = $this.attr('data-old');
                                        if (typeof fieldOld === 'undefined') {
                                            $this.attr('data-old', field);
                                        }
                                        if (field != fieldOld) {
                                            $this.addClass('animate');
                                            window.setTimeout(function() {
                                                $flipFront.text(field);
                                                $this
                                                        .removeClass('animate')
                                                        .attr('data-old', field);
                                            }, 800);
                                        }
                                    });
                                }
                                if (navigator.userAgent.indexOf('MSIE') !== -1 || navigator.appVersion.indexOf('Trident/') > 0) {
                                    $('html').addClass('internet-explorer');
                                }

                                options = {
                                    hoursWrapper: '.ce-hours .ce-flip-back',
                                    minutesWrapper: '.ce-minutes .ce-flip-back',
                                    secondsWrapper: '.ce-seconds .ce-flip-back',
                                    wrapDigits: false,
                                    onChange: function() {
                                        countEverestFlipAnimate($(element.dom_element).find('.countdown .col>div'), this);
                                    }
                                }
                                break;
                            case 'style7':
                                function countEverestAnimate($el) {
                                    $el.each(function(index) {
                                        var $this = $(this),
                                                fieldText = $this.text(),
                                                fieldData = $this.attr('data-value'),
                                                fieldOld = $this.attr('data-old');

                                        if (typeof fieldOld === 'undefined') {
                                            $this.attr('data-old', fieldText);
                                        }

                                        if (fieldText != fieldData) {

                                            $this
                                                    .attr('data-value', fieldText)
                                                    .attr('data-old', fieldData)
                                                    .addClass('animate');

                                            window.setTimeout(function() {
                                                $this
                                                        .removeClass('animate')
                                                        .attr('data-old', fieldText);
                                            }, 300);
                                        }
                                    });
                                }
                                options = {
                                    onChange: function() {
                                        countEverestAnimate($(element.dom_element).find(".countdown").find('.number span'));
                                    }
                                }
                                break;
                            case 'style9':
                                function deg(v) {
                                    return (Math.PI / 180) * v - (Math.PI / 2);
                                }

                                function drawCircle(canvas, value, max) {
                                    var circle = canvas.getContext('2d');

                                    circle.clearRect(0, 0, canvas.width, canvas.height);
                                    circle.lineWidth = 4;

                                    circle.beginPath();
                                    circle.arc(
                                            canvas.width / 2,
                                            canvas.height / 2,
                                            canvas.width / 2 - circle.lineWidth,
                                            deg(0),
                                            deg(360 / max * (max - value)),
                                            false);
                                    circle.strokeStyle = '#282828';
                                    circle.stroke();

                                    circle.beginPath();
                                    circle.arc(
                                            canvas.width / 2,
                                            canvas.height / 2,
                                            canvas.width / 2 - circle.lineWidth,
                                            deg(0),
                                            deg(360 / max * (max - value)),
                                            true);
                                    circle.strokeStyle = '#117d8b';
                                    circle.stroke();
                                }
                                options = {
                                    leftHandZeros: false,
                                    onChange: function() {
                                        drawCircle($(element.dom_element).find('#days').get(0), this.days, 365);
                                        drawCircle($(element.dom_element).find('#hours').get(0), this.hours, 24);
                                        drawCircle($(element.dom_element).find('#minutes').get(0), this.minutes, 60);
                                        drawCircle($(element.dom_element).find('#seconds').get(0), this.seconds, 60);
                                    }
                                }
                                break;
                            case 'style10':
                                var $countdown = $(element.dom_element).find('.countdown');
                                var firstCalculation = true;
                                options = {
                                    leftHandZeros: true,
                                    dayLabel: null,
                                    hourLabel: null,
                                    minuteLabel: null,
                                    secondLabel: null,
                                    afterCalculation: function() {
                                        var plugin = this,
                                                units = {
                                                    days: this.days,
                                                    hours: this.hours,
                                                    minutes: this.minutes,
                                                    seconds: this.seconds
                                                },
                                        //max values per unit
                                        maxValues = {
                                            hours: '23',
                                            minutes: '59',
                                            seconds: '59'
                                        },
                                        actClass = 'active',
                                                befClass = 'before';

                                        //build necessary elements
                                        if (firstCalculation == true) {
                                            firstCalculation = false;

                                            //build necessary markup
                                            $countdown.find('.unit-wrap div').each(function() {
                                                var $this = $(this),
                                                        className = $this.attr('class'),
                                                        value = units[className],
                                                        sub = '',
                                                        dig = '';

                                                //build markup per unit digit
                                                for (var x = 0; x < 10; x++) {
                                                    sub += [
                                                        '<div class="digits-inner">',
                                                        '<div class="flip-wrap">',
                                                        '<div class="up">',
                                                        '<div class="shadow"></div>',
                                                        '<div class="inn">' + x + '</div>',
                                                        '</div>',
                                                        '<div class="down">',
                                                        '<div class="shadow"></div>',
                                                        '<div class="inn">' + x + '</div>',
                                                        '</div>',
                                                        '</div>',
                                                        '</div>'
                                                    ].join('');
                                                }

                                                //build markup for number
                                                for (var i = 0; i < value.length; i++) {
                                                    dig += '<div class="digits">' + sub + '</div>';
                                                }
                                                $this.append(dig);
                                            });
                                        }

                                        //iterate through units
                                        $.each(units, function(unit) {
                                            var digitCount = $countdown.find('.' + unit + ' .digits').length,
                                                    maxValueUnit = maxValues[unit],
                                                    maxValueDigit,
                                                    value = plugin.strPad(this, digitCount, '0');

                                            //iterate through digits of an unit
                                            for (var i = value.length - 1; i >= 0; i--) {
                                                var $digitsWrap = $countdown.find('.' + unit + ' .digits:eq(' + (i) + ')'),
                                                        $digits = $digitsWrap.find('div.digits-inner');

                                                //use defined max value for digit or simply 9
                                                if (maxValueUnit) {
                                                    maxValueDigit = (maxValueUnit[i] == 0) ? 9 : maxValueUnit[i];
                                                } else {
                                                    maxValueDigit = 9;
                                                }

                                                //which numbers get the active and before class
                                                var activeIndex = parseInt(value[i]),
                                                        beforeIndex = (activeIndex == maxValueDigit) ? 0 : activeIndex + 1;

                                                //check if value change is needed
                                                if ($digits.eq(beforeIndex).hasClass(actClass)) {
                                                    $digits.parent().addClass('play');
                                                }

                                                //remove all classes
                                                $digits
                                                        .removeClass(actClass)
                                                        .removeClass(befClass);

                                                //set classes
                                                $digits.eq(activeIndex).addClass(actClass);
                                                $digits.eq(beforeIndex).addClass(befClass);
                                            }
                                        });
                                    }
                                }
                                break;
                        }
                        switch (element.attrs['counter_scope']) {
                            case 'date':
                                var d = Date.parseDate(element.attrs['date'], 'd.m.Y');
                                if (d != null)
                                    $(element.dom_element).countEverest($.extend(options, {
                                        day: d.getDate(),
                                        month: d.getMonth() + 1,
                                        year: d.getFullYear(),
                                    }));
                                break;
                            case 'date_time':
                                var d = Date.parseDate(element.attrs['date_time'], 'd.m.Y H');
                                if (d != null)
                                    $(element.dom_element).countEverest($.extend(options, {
                                        day: d.getDate(),
                                        month: d.getMonth() + 1,
                                        year: d.getFullYear(),
                                        hour: d.getHours()
                                    }));
                                break;
                            case 'repeating':
                                var d = new Date();
                                d.setHours(element.attrs['time']);
                                if (d != null)
                                    $(element.dom_element).countEverest($.extend(options, {
                                        day: d.getDate(),
                                        month: d.getMonth() + 1,
                                        year: d.getFullYear(),
                                        hour: d.getHours(),
                                        onComplete: function() {
                                            if (element.attrs['referrer'] != '') {
                                                window.location.replace(element.attrs['referrer']);
                                            }
                                        }
                                    }));
                                break;
                            case 'resetting':
                                if (element.attrs['saved'] != '') {
                                    var saved = new Date(element.attrs['saved']);
                                    var interval = (Math.round(element.attrs['reset_hours']) * 60 * 60 + Math.round(element.attrs['reset_minutes']) * 60 + Math.round(element.attrs['reset_seconds'])) * 1000;
                                    if (element.attrs['restart'] == 'yes') {
                                        var current = new Date();
                                        var elapsed = current.getTime() - saved.getTime();
                                        var k = elapsed / interval;
                                        elapsed = elapsed - Math.floor(k) * interval;
                                        var delta = interval - elapsed;
                                        var d = new Date(current.getTime() + delta);
                                        $(element.dom_element).countEverest($.extend(options, {
                                            day: d.getDate(),
                                            month: d.getMonth() + 1,
                                            year: d.getFullYear(),
                                            hour: d.getHours(),
                                            minute: d.getMinutes(),
                                            second: d.getSeconds(),
                                            onComplete: function() {
                                                if (element.attrs['referrer'] != '') {
                                                    window.location.replace(element.attrs['referrer']);
                                                }
                                            }
                                        }));
                                    } else {
                                        var d = new Date(saved.getTime() + interval);
                                        $(element.dom_element).countEverest($.extend(options, {
                                            day: d.getDate(),
                                            month: d.getMonth() + 1,
                                            year: d.getFullYear(),
                                            hour: d.getHours(),
                                            minute: d.getMinutes(),
                                            second: d.getSeconds(),
                                            onComplete: function() {
                                                if (element.attrs['referrer'] != '') {
                                                    window.location.replace(element.attrs['referrer']);
                                                }
                                            }
                                        }));
                                    }
                                }
                                break;
                            default:
                                break;
                        }
                    }});
            },
            render: function($, p, fp) {
                
                this.dom_element = $('<div class="az-element az-countdown ' + this.attrs['el_class'] + '" style="' + this.attrs['style'] + '"></div>');
                var countdown = $('<div class="countdown"></div>').appendTo(this.dom_element);
                switch (this.attrs['countdown_style']) {
                    case 'style1':
                        $(this.dom_element).addClass('example--1');
                        if (_.indexOf(this.attrs['display'].split(','), 'days') >= 0)
                            $(countdown).append('<div class="col"><span class="ce-days"></span> <span class="ce-days-label"></span></div>');
                        if (_.indexOf(this.attrs['display'].split(','), 'hours') >= 0)
                            $(countdown).append('<div class="col"><span class="ce-hours"></span> <span class="ce-hours-label"></span></div>');
                        if (_.indexOf(this.attrs['display'].split(','), 'minutes') >= 0)
                            $(countdown).append('<div class="col"><span class="ce-minutes"></span> <span class="ce-minutes-label"></span></div>');
                        if (_.indexOf(this.attrs['display'].split(','), 'seconds') >= 0)
                            $(countdown).append('<div class="col"><span class="ce-seconds"></span> <span class="ce-seconds-label"></span></div>');
                        break;
                    case 'style2':
                        $(this.dom_element).addClass('example--2');
                        if (_.indexOf(this.attrs['display'].split(','), 'days') >= 0)
                            $(countdown).append('<div class="bar bar-days"><div class="fill"></div></div><span class="ce-days"></span> <span class="ce-days-label"></span>');
                        if (_.indexOf(this.attrs['display'].split(','), 'hours') >= 0)
                            $(countdown).append('<div class="bar bar-hours"><div class="fill"></div></div><span class="ce-hours"></span> <span class="ce-hours-label"></span>');
                        if (_.indexOf(this.attrs['display'].split(','), 'minutes') >= 0)
                            $(countdown).append('<div class="bar bar-minutes"><div class="fill"></div></div><span class="ce-minutes"></span> <span class="ce-minutes-label"></span>');
                        if (_.indexOf(this.attrs['display'].split(','), 'seconds') >= 0)
                            $(countdown).append('<div class="bar bar-seconds"><div class="fill"></div></div><span class="ce-seconds"></span> <span class="ce-seconds-label"></span>');
                        break;
                    case 'style3':
                        $(this.dom_element).addClass('example--3');
                        if (_.indexOf(this.attrs['display'].split(','), 'hours') >= 0)
                            $(countdown).append('<span class="number ce-hours"></span>');
                        if (_.indexOf(this.attrs['display'].split(','), 'minutes') >= 0)
                            $(countdown).append('<span class="number ce-minutes"></span>');
                        if (_.indexOf(this.attrs['display'].split(','), 'seconds') >= 0)
                            $(countdown).append('<span class="number ce-seconds"></span>');
                        break;
                    case 'style4':
                        $(this.dom_element).addClass('example--4');
                        if (_.indexOf(this.attrs['display'].split(','), 'days') >= 0)
                            $(countdown).append('<span class="ce-days"></span> <span class="ce-days-label"></span>');
                        break;
                    case 'style5':
                        $(this.dom_element).addClass('example--5');
                        if (_.indexOf(this.attrs['display'].split(','), 'minutes') >= 0)
                            $(countdown).append('<span class="ce-minutes"></span>:');
                        if (_.indexOf(this.attrs['display'].split(','), 'seconds') >= 0)
                            $(countdown).append('<span class="ce-seconds"></span>');
                        break;
                    case 'style6':
                        $(this.dom_element).addClass('example--6');
                        if (_.indexOf(this.attrs['display'].split(','), 'hours') >= 0)
                            $(countdown).append('<div class="col"><div class="ce-hours"><div class="ce-flip-wrap"><div class="ce-flip-front"></div><div class="ce-flip-back"></div></div></div><span class="ce-hours-label"></span></div>');
                        if (_.indexOf(this.attrs['display'].split(','), 'minutes') >= 0)
                            $(countdown).append('<div class="col"><div class="ce-minutes"><div class="ce-flip-wrap"><div class="ce-flip-front"></div><div class="ce-flip-back"></div></div></div><span class="ce-minutes-label"></span></div>');
                        if (_.indexOf(this.attrs['display'].split(','), 'seconds') >= 0)
                            $(countdown).append('<div class="col"><div class="ce-seconds"><div class="ce-flip-wrap"><div class="ce-flip-front"></div><div class="ce-flip-back"></div></div></div><span class="ce-seconds-label"></span></div>');
                        break;
                    case 'style7':
                        $(this.dom_element).addClass('example--7');
                        if (_.indexOf(this.attrs['display'].split(','), 'hours') >= 0)
                            $(countdown).append('<span class="number ce-hours"></span> :');
                        if (_.indexOf(this.attrs['display'].split(','), 'minutes') >= 0)
                            $(countdown).append('<span class="number ce-minutes"></span> :');
                        if (_.indexOf(this.attrs['display'].split(','), 'seconds') >= 0)
                            $(countdown).append('<span class="number ce-seconds"></span>');
                        break;
                    case 'style8':
                        $(this.dom_element).addClass('example--8');
                        if (_.indexOf(this.attrs['display'].split(','), 'days') >= 0)
                            $(countdown).append('<span class="ce-days"></span> <span class="ce-days-label"></span>');
                        break;
                    case 'style9':
                        $(this.dom_element).addClass('example--9');
                        if (_.indexOf(this.attrs['display'].split(','), 'days') >= 0)
                            $(countdown).append('<div class="circle"><canvas id="days" width="408" height="408"></canvas><div class="circle__values"><span class="ce-digit ce-days"></span><span class="ce-label ce-days-label"></span></div></div>');
                        if (_.indexOf(this.attrs['display'].split(','), 'hours') >= 0)
                            $(countdown).append('<div class="circle"><canvas id="hours" width="408" height="408"></canvas><div class="circle__values"><span class="ce-digit ce-hours"></span><span class="ce-label ce-hours-label"></span></div></div>');
                        if (_.indexOf(this.attrs['display'].split(','), 'minutes') >= 0)
                            $(countdown).append('<div class="circle"><canvas id="minutes" width="408" height="408"></canvas><div class="circle__values"><span class="ce-digit ce-minutes"></span><span class="ce-label ce-minutes-label"></span></div></div>');
                        if (_.indexOf(this.attrs['display'].split(','), 'seconds') >= 0)
                            $(countdown).append('<div class="circle"><canvas id="seconds" width="408" height="408"></canvas><div class="circle__values"><span class="ce-digit ce-seconds"></span><span class="ce-label ce-seconds-label"></span></div></div>');
                        break;
                    case 'style10':
                        $(this.dom_element).addClass('example--10');
                        if (_.indexOf(this.attrs['display'].split(','), 'days') >= 0)
                            $(countdown).append('<div class="unit-wrap"><div class="days"></div><span class="ce-days-label"></span></div>');
                        if (_.indexOf(this.attrs['display'].split(','), 'hours') >= 0)
                            $(countdown).append('<div class="unit-wrap"><div class="hours"></div><span class="ce-hours-label"></span></div>');
                        if (_.indexOf(this.attrs['display'].split(','), 'minutes') >= 0)
                            $(countdown).append('<div class="unit-wrap"><div class="minutes"></div><span class="ce-minutes-label"></span></div>');
                        if (_.indexOf(this.attrs['display'].split(','), 'seconds') >= 0)
                            $(countdown).append('<div class="unit-wrap"><div class="seconds"></div><span class="ce-seconds-label"></span></div>');
                        break;
                    default:
                        if (_.indexOf(this.attrs['display'].split(','), 'days') >= 0)
                            $(countdown).append('<span class="ce-days"></span> <span class="ce-days-label"></span>');
                        if (_.indexOf(this.attrs['display'].split(','), 'hours') >= 0)
                            $(countdown).append('<span class="ce-hours"></span> <span class="ce-hours-label"></span>');
                        if (_.indexOf(this.attrs['display'].split(','), 'minutes') >= 0)
                            $(countdown).append('<span class="ce-minutes"></span> <span class="ce-minutes-label"></span>');
                        if (_.indexOf(this.attrs['display'].split(','), 'seconds') >= 0)
                            $(countdown).append('<span class="ce-seconds"></span> <span class="ce-seconds-label"></span>');
                        break;
                }
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_circle_counter',
            name: t('Circle counter'),
            icon: 'fa fa-circle-o-notch',
            description: t('Place circle counter element'),
            params: [
                {
                    type: 'colorpicker',
                    heading: t('Foreground color'),
                    param_name: 'fgcolor',
                    description: t('Is the foreground color of the circle.'),
                },
                {
                    type: 'colorpicker',
                    heading: t('Background color'),
                    param_name: 'bgcolor',
                    description: t('Is the background color of the cicle.'),
                },
                {
                    type: 'colorpicker',
                    heading: t('Fill'),
                    param_name: 'fill',
                    description: t('Is the background color of the whole circle (can be empty if you dont want to set a background to the whole circle).'),
                },
                {
                    type: 'checkbox',
                    heading: t('Half?'),
                    param_name: 'type',
                    description: t('Full or half circle for example data-type="half" if not set the circle will be a full circle.'),
                    value: {
                        'half': t("Yes, please"),
                    },
                },
                {
                    type: 'integer_slider',
                    heading: t('Dimension'),
                    param_name: 'dimension',
                    max: '500',
                    description: t('Is the height and width of the element.'),
                    value: '250',
                },
                {
                    type: 'textfield',
                    heading: t('Text'),
                    param_name: 'text',
                    description: t('Will be displayed inside of the circle over the info element.'),
                    tab: t('Circle content'),
                },
                {
                    type: 'integer_slider',
                    heading: t('Font size'),
                    param_name: 'fontsize',
                    max: '100',
                    description: t('Is the font size for the text element.'),
                    value: '16',
                    tab: t('Circle content'),
                },
                {
                    type: 'textfield',
                    heading: t('Info'),
                    param_name: 'info',
                    description: t('Will be deisplayed inside of the circle bellow the text element (can be empty if you dont want to show info text).'),
                    tab: t('Circle content'),
                },
                {
                    type: 'integer_slider',
                    heading: t('Width'),
                    param_name: 'width',
                    max: '100',
                    description: t('Is the thickness of circle.'),
                    value: '5',
                },
                {
                    type: 'integer_slider',
                    heading: t('Percent'),
                    param_name: 'percent',
                    description: t('Can be 1 to 100.'),
                    value: '50',
                },
                {
                    type: 'dropdown',
                    heading: t('Border'),
                    param_name: 'border',
                    description: t('Will change the styling of the circle. The line for showing the percentage value will be displayed inline or outline.'),
                    value: {
                        'default': t('Default'),
                        'inline': t('Inline'),
                        'outline': t('Outline'),
                    },
                },
                {
                    type: 'icon',
                    heading: t('Icon'),
                    param_name: 'icon',
                    tab: t('Icon'),
                },
                {
                    type: 'integer_slider',
                    heading: t('Icon size'),
                    param_name: 'icon_size',
                    max: '100',
                    description: t('Will set the font size of the icon.'),
                    value: '16',
                    tab: t('Icon'),
                },
                {
                    type: 'colorpicker',
                    heading: t('Icon color'),
                    param_name: 'icon_color',
                    description: t('Will set the font color of the icon.'),
                    tab: t('Icon'),
                },
            ],
            show_settings_on_create: true,
            frontend_render: true,
            showed: function($, p, fp) {
                
                this.baseclass.prototype.showed.apply(this, arguments);
                var element = this;
                this.add_css('css/jquery.circliful.css', 'circliful' in $.fn, function() {
                });
                this.add_js_list({
                    paths: ['js/jquery.circliful.min.js', 'jquery-waypoints/waypoints.min.js'],
                    loaded: 'waypoint' in $.fn && 'circliful' in $.fn,
                    callback: function() {
                        $(element.dom_element).waypoint(function(direction) {
                            $(element.dom_element).find('#' + element.id).circliful();
                        }, {offset: '100%', triggerOnce: true});
                        $(document).trigger('scroll');
                    }});
            },
            render: function($, p, fp) {
                
                this.dom_element = $('<div class="az-element az-circle-counter ' + this.attrs['el_class'] + '" style="' + this.attrs['style'] + '"><div id="' + this.id + '" data-dimension="' + this.attrs['dimension']
                        + '" data-text="' + this.attrs['text']
                        + '" data-info="' + this.attrs['info']
                        + '" data-width="' + this.attrs['width']
                        + '" data-fontsize="' + this.attrs['fontsize']
                        + '" data-type="' + this.attrs['type']
                        + '" data-percent="' + this.attrs['percent']
                        + '" data-fgcolor="' + this.attrs['fgcolor']
                        + '" data-bgcolor="' + this.attrs['bgcolor']
                        + '" data-border="' + this.attrs['border']
                        + '" data-icon=" ' + this.attrs['icon']
                        + '" data-icon-size="' + this.attrs['icon_size']
                        + '" data-icon-color="' + this.attrs['icon_color']
                        + '" ></div></div>');
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_counter',
            name: t('Counter'),
            icon: 'fa fa-flag',
            description: t('Place counter element'),
            params: [
                {
                    type: 'textfield',
                    heading: t('Start'),
                    param_name: 'start',
                    description: t('Enter the number to start counting from.'),
                    value: '0',
                },
                {
                    type: 'textfield',
                    heading: t('End'),
                    param_name: 'end',
                    description: t('Enter the number to count up to.'),
                    value: '100',
                },
                {
                    type: 'integer_slider',
                    heading: t('Font Size'),
                    param_name: 'fontsize',
                    max: '200',
                    description: t('Select the font size for the counter number.'),
                    value: '30',
                },
                {
                    type: 'integer_slider',
                    heading: t('Speed'),
                    param_name: 'speed',
                    max: '10000',
                    description: t('Select the speed in ms for the counter to finish.'),
                    value: '2000',
                },
                {
                    type: 'dropdown',
                    heading: t('Thousand Seperator'),
                    param_name: 'seperator',
                    description: t('Select a character to seperate thousands in the end number.'),
                    value: {
                        '': t('None'),
                        ',': t('Comma'),
                        '.': t('Dot'),
                        ' ': t('Space'),
                    },
                },
                {
                    type: 'textfield',
                    heading: t('Prefix'),
                    param_name: 'prefix',
                    description: t('Enter any character to be shown before the nunber (i.e. $).'),
                },
                {
                    type: 'textfield',
                    heading: t('Postfix'),
                    param_name: 'postfix',
                    description: t('Enter any character to be shown after the nunber (i.e. %).'),
                },
            ],
            show_settings_on_create: true,
            showed: function($, p, fp) {
                
                this.baseclass.prototype.showed.apply(this, arguments);
                var element = this;
                this.add_js_list({
                    paths: ['js/jquery.countTo.min.js', 'jquery-waypoints/waypoints.min.js'],
                    loaded: 'waypoint' in $.fn && 'countTo' in $.fn,
                    callback: function() {
                        $(element.dom_element).waypoint(function(direction) {
                            $(element.dom_element).find('#' + element.id).countTo({
                                from: Math.round(element.attrs['start']),
                                to: Math.round(element.attrs['end']),
                                speed: Math.round(element.attrs['speed']),
                                refreshInterval: 50,
                                decimals: Math.round(element.attrs['seperator']),
                                formatter: function(t, e) {
                                    return element.attrs['prefix'] + t.toFixed(e.decimals) + element.attrs['postfix'];
                                }});
                        }, {offset: '100%', triggerOnce: true});
                        $(document).trigger('scroll');

//                $(element.dom_element).waypoint({
//                    handler: function() {
//                    }
//                });
                    }});
            },
            render: function($, p, fp) {
                
                this.dom_element = $('<div class="az-element az-counter"><div id="' + this.id + '" class="' + this.attrs['el_class'] + '" style="' + this.attrs['style'] + '">' + this.attrs['start'] + '</div></div>');
                $(this.dom_element).find('#' + this.id).css('font-size', this.attrs['fontsize'] + 'px');
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_tagged_images_grid',
            name: t('Tagged images grid'),
            icon: 'fa fa-magic',
            description: t('Tagged images grid'),
            category: t('Wizard'),
            params: [
                {
                    type: 'tagged_images',
                    heading: t('Images with tags'),
                    param_name: 'images_with_tags',
                },
            ],
            show_settings_on_create: true,
            is_container: true,
            show_controls: function() {
                if (window.azexo_editor) {
                    this.baseclass.prototype.show_controls.apply(this, arguments);
                    $(this.controls).find('.add').remove();
                    $(this.controls).find('.paste').remove();
                }
            },
            showed: function($, p, fp) {
                
                this.baseclass.prototype.showed.apply(this, arguments);
                if (this.children.length == 0 && this.attrs['images_with_tags'] != '') {
                    var shortcode = '[az_grid]';
                    if (this.attrs['images_with_tags'] != '') {
                        var rows = this.attrs['images_with_tags'].split('}');
                        for (var i = 0; i < rows.length; i++) {
                            if (rows[i] != '') {
                                var row = rows[i].split('{');
                                shortcode += '[az_item tags="' + row[1] + '"][az_layers height="250px" style="background-image: url(' + row[0] + ');background-position: center;background-repeat: no-repeat;background-size: cover;" ][/az_layers][/az_item]';
                            }
                        }
                    }
                    shortcode += '[/az_grid]';
                    this.children = [];
                    this.parse_shortcode(shortcode);
                    this.children[0].recursive_render();
                    this.attach_children();
                    this.children[0].recursive_showed();
                    if (window.azexo_editor)
                        this.update_empty();
                }
            },
            render: function($, p, fp) {
                
                this.dom_element = $('<div class="az-element az-wizard az-tagged-images-grid ' + this.attrs['el_class'] + '" style="' + this.attrs['style'] + '"></div>');
                this.dom_content_element = this.dom_element;
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
    ];

    if ('azexo_elements' in window) {
        window.azexo_elements = window.azexo_elements.concat(azexo_elements);
    } else {
        window.azexo_elements = azexo_elements;
    }

    var azexo_form_elements = [
        {
            base: 'az_dropdown',
            name: t('Dropdown'),
            icon: 'fa fa-level-down',
            description: t('Form dropdown field'),
            params: [
                {
                    type: 'html',
                    heading: t('Options'),
                    param_name: 'options',
                    description: t('Separated by new line.'),
                },
            ],
            hidden: true,
            show_settings_on_create: true,
            render: function($, p, fp) {
                
                
                var required = (this.attrs['required'] == 'yes') ? 'required' : '';
                var select = '<select name="' + btoa(encodeURIComponent(this.attrs['name'])) + '" class="' + p + 'form-control" ' + required + '>';
                select += '<option value="">' + t('Please select') + '</option>';
                var options = this.attrs['options'].split("\n");
                for (var i = 0; i < options.length; i++) {
                    select += '<option value="' + options[i] + '">' + options[i] + '</option>';
                }
                select += '/<select>';
                this.dom_element = $('<div class="az-element az-dropdown ' + p + 'form-group' + this.attrs['el_class'] + '" style="' + this.attrs['style'] + '" ><label>' + this.attrs['name'] + '</label><div>' + select + '</div></div>');
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_checkbox',
            name: t('Checkbox'),
            icon: 'fa fa-check-square-o',
            description: t('Form Checkbox field'),
            params: [
                {
                    type: 'html',
                    heading: t('Options'),
                    param_name: 'options',
                    description: t('Separated by new line.'),
                },
            ],
            hidden: true,
            show_settings_on_create: true,
            render: function($, p, fp) {
                
                
                var inputs = '';
                var options = this.attrs['options'].split("\n");
                for (var i = 0; i < options.length; i++) {
                    inputs += '<div class="' + p + 'checkbox"><label><input name="' + btoa(encodeURIComponent(options[i])) + '" type="checkbox" value="' + options[i] + '">' + options[i] + '</label></div>';
                }
                this.dom_element = $('<div class="az-element az-checkbox ' + p + 'form-group' + this.attrs['el_class'] + '" style="' + this.attrs['style'] + '"><label>' + this.attrs['name'] + '</label><div>' + inputs + '</div></div>');
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_textfield',
            name: t('Textfield'),
            icon: 'fa fa-pencil-square-o',
            description: t('Form text field'),
            params: [
            ],
            hidden: true,
            show_settings_on_create: true,
            render: function($, p, fp) {
                
                
                var required = (this.attrs['required'] == 'yes') ? 'required' : '';
                this.dom_element = $('<div class="az-element az-textfield ' + p + 'form-group' + this.attrs['el_class'] + '" style="' + this.attrs['style'] + '"><div><input class="' + p + 'form-control" name="' + btoa(encodeURIComponent(this.attrs['name'])) + '" type="text" placeholder="' + this.attrs['name'] + '" ' + required + '></div></div>');
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_number',
            name: t('Number'),
            icon: 'fa fa-arrows-h',
            description: t('Form number field with slider'),
            params: [
                {
                    type: 'textfield',
                    heading: t('Minimum'),
                    param_name: 'minimum',
                    description: t('Required.'),
                    required: true,
                    value: '0',
                },
                {
                    type: 'textfield',
                    heading: t('Maximum'),
                    param_name: 'maximum',
                    description: t('Required.'),
                    required: true,
                    value: '100',
                },
                {
                    type: 'textfield',
                    heading: t('Step'),
                    param_name: 'step',
                    description: t('Required.'),
                    required: true,
                    value: '1',
                },
            ],
            hidden: true,
            show_settings_on_create: true,
            showed: function($, p, fp) {
                
                this.baseclass.prototype.showed.apply(this, arguments);
                function nouislider(slider, min, max, value, step, target) {
                    azexo_add_css('nouislider/jquery.nouislider.css', function() {
                    });
                    azexo_add_js({
                        path: 'nouislider/jquery.nouislider.min.js',
                        callback: function() {
                            $(slider).noUiSlider({
                                range: {
                                    min: parseFloat(min),
                                    max: parseFloat(max)
                                },
                                start: (value == '') ? NaN : parseFloat(value),
                                handles: 1,
                                step: parseFloat(step),
                                behaviour: "extend-tap",
                                serialization: {
                                    lower: [$.Link({
                                            target: target
                                        })],
                                },
                                slide: function() {
                                },
                                set: function() {
                                }
                            }).change(function() {
                            });
                        }
                    });
                }
                nouislider($(this.dom_element).find('.slider'), this.attrs['minimum'], this.attrs['maximum'], '', this.attrs['step'], $(this.dom_element).find('input'));
            },
            render: function($, p, fp) {
                
                
                var required = (this.attrs['required'] == 'yes') ? 'required' : '';
                this.dom_element = $('<div class="az-element az-number ' + p + 'form-group' + this.attrs['el_class'] + '" style="' + this.attrs['style'] + '"><div><input class="' + p + 'form-control" name="' + btoa(encodeURIComponent(this.attrs['name'])) + '" type="text" ' + required + ' placeholder="' + this.attrs['name'] + '"></div><div class="slider"></div></div>');
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_date',
            name: t('Date'),
            icon: 'fa fa-calendar',
            description: t('Form date field'),
            params: [
                {
                    type: 'checkbox',
                    heading: t('Time is enabled?'),
                    param_name: 'time',
                    value: {
                        'yes': t("Yes, please"),
                    },
                },
            ],
            hidden: true,
            show_settings_on_create: true,
            frontend_render: true,
            showed: function($, p, fp) {
                
                this.baseclass.prototype.showed.apply(this, arguments);
                var element = this;
                this.add_css('datetimepicker/jquery.datetimepicker.css', function() {
                });
                this.add_js({
                    path: 'datetimepicker/jquery.datetimepicker.js',
                    callback: function() {
                        function lang() {
                            if ('azexo_lang' in window) {
                                return window.azexo_lang;
                            } else {
                                return 'en';
                            }
                        }
                        $(element.dom_element).find('input').datetimepicker({
                            format: (element.attrs['time'] == 'yes') ? 'Y/m/d H:i' : 'Y/m/d',
                            timepicker: (element.attrs['time'] == 'yes'),
                            datepicker: true,
                            inline: true,
                            lang: lang()
                        });
                    }
                });
            },
            render: function($, p, fp) {
                
                var required = (this.attrs['required'] == 'yes') ? 'required' : '';
                this.dom_element = $('<div class="az-element az-date ' + p + 'form-group' + this.attrs['el_class'] + '" style="' + this.attrs['style'] + '"><label>' + this.attrs['name'] + '</label><div><input class="' + p + 'form-control" name="' + btoa(encodeURIComponent(this.attrs['name'])) + '" type="text" ' + required + '></div></div>');
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
        {
            base: 'az_textarea',
            name: t('Textarea'),
            icon: 'fa fa-file-text-o',
            description: t('Form text area'),
            params: [
            ],
            hidden: true,
            show_settings_on_create: true,
            render: function($, p, fp) {
                
                var required = (this.attrs['required'] == 'yes') ? 'required' : '';
                this.dom_element = $('<div class="az-element az-textarea ' + p + 'form-group' + this.attrs['el_class'] + '" style="' + this.attrs['style'] + '"><div><textarea class="' + p + 'form-control" rows="10" cols="45" name="' + btoa(encodeURIComponent(this.attrs['name'])) + '" " placeholder="' + this.attrs['name'] + '" ' + required + '></textarea></div></div>');
                this.baseclass.prototype.render.apply(this, arguments);
            },
        },
    ];
    if ('azexo_form_elements' in window) {
        window.azexo_form_elements = window.azexo_form_elements.concat(azexo_form_elements);
    } else {
        window.azexo_form_elements = azexo_form_elements;
    }

})(window.jQuery);
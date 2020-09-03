<?php

$section  = 'header_background_chooser';
$priority = 7;

$group = 'slider_pagination_options_group_button';

mesmerize_add_kirki_field(array(
    'type'            => 'checkbox',
    'label'           => esc_html__('Show pagination', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_enable_pagination',
    'priority'        => $priority,
    'default'         => true,
    'transport'       => 'postMessage',
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_navigation',
            'operator' => '==',
            'value'    => true,
        ),
        array(
            'setting'  => 'header_type',
            'operator' => '==',
            'value'    => 'slider',
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'sidebar-button-group',
    'label'           => esc_html__('Options', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_pagination_options_group_button',
    'priority'        => $priority,
    'in_row_with'     => array('slider_enable_pagination'),
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_navigation',
            'operator' => '==',
            'value'    => true,
        ),
        array(
            'setting'  => 'slider_enable_pagination',
            'operator' => '==',
            'value'    => true,
        ),
        array(
            'setting'  => 'header_type',
            'operator' => '==',
            'value'    => 'slider',
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'sectionseparator',
    'label'           => esc_html__('Pagination Options', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_pagination_options_separator',
    'priority'        => $priority,
    'group'           => $group,
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_pagination',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

//mesmerize_add_kirki_field(array(
//    'type'            => 'slider',
//    'label'           => esc_html__('Pagination Top Offset', 'mesmerize'),
//    'section'         => $section,
//    'settings'        => 'slider_pagination_top_offset',
//    'priority'        => $priority,
//    'default'         => 5,
//    'choices'         => array(
//        'min'  => '0',
//        'max'  => '15',
//        'step' => '1',
//    ),
//    'group'           => $group,
//    'transport'       => 'postMessage',
//    'output'          => array(
//        array(
//            'element'  => '.header-slider-navigation .owl-dots',
//            'property' => 'margin-top',
//            'units'    => 'px',
//        ),
//    ),
//    'js_vars'         => array(
//        array(
//            'element'  => '.header-slider-navigation .owl-dots',
//            'function' => 'css',
//            'property' => 'margin-top',
//            'units'    => 'px',
//        ),
//    ),
//    'active_callback' => array(
//        array(
//            'setting'  => 'slider_enable_pagination',
//            'operator' => '==',
//            'value'    => true,
//        ),
//
//    ),
//));

mesmerize_add_kirki_field(array(
    'type'            => 'select',
    'label'           => esc_html__('Pagination Position', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_pagination_position',
    'priority'        => $priority,
    'default'         => 'bottom',
    'choices'         => array(
        'top'    => esc_html__('top', 'mesmerize'),
        'bottom' => esc_html__('bottom', 'mesmerize'),
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_pagination',
            'operator' => '==',
            'value'    => true,
        ),

    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_html__('Pagination Offset', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_pagination_offset',
    'priority'        => $priority,
    'default'         => 0,
    'choices'         => array(
        'min'  => '0',
        'max'  => '50',
        'step' => '1',
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => '.header-slider-navigation .owl-dots',
            'property' => 'margin-bottom',
            'units'    => 'px',
        ),

        array(
            'element'  => '.header-slider-navigation .owl-dots',
            'property' => 'margin-top',
            'units'    => 'px',
        ),

    ),
    'js_vars'         => array(
        array(
            'element'  => '.header-slider-navigation .owl-dots',
            'function' => 'css',
            'property' => 'margin-bottom',
            'units'    => 'px',
        ),

        array(
            'element'  => '.header-slider-navigation .owl-dots',
            'function' => 'css',
            'property' => 'margin-top',
            'units'    => 'px',
        ),

    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_pagination',
            'operator' => '==',
            'value'    => true,
        ),

    ),
));

/*
mesmerize_add_kirki_field(array(
    'type'            => 'radio',
    'label'           => esc_html__('Pagination Type', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_pagination_type',
    'priority'        => $priority,
    'default'         => 'shapes',
    'choices'         => array(
        'shapes'           => 'Shapes',
        'thumbnails'       => 'Thumbnails',
    ),
    'group'           => $group,
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_pagination',
            'operator' => '==',
            'value'    => true,
        ),
        array(
            'setting'  => 'slider_group_navigation',
            'operator' => '==',
            'value'    => false,
        ),
    ),
));
*/

mesmerize_add_kirki_field(array(
    'type'            => 'select',
    'label'           => esc_html__('Shapes Type', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_pagination_shapes_type',
    'priority'        => $priority,
    'default'         => 'medium-circles',
    'choices'         => array(
        'small-circles'                    => esc_html__('Small circles', 'mesmerize'),
        'medium-circles'                   => esc_html__('Medium circles', 'mesmerize'),
        'large-circles'                    => esc_html__('Large circles', 'mesmerize'),
        'small-squares'                    => esc_html__('Small squares', 'mesmerize'),
        'medium-squares'                   => esc_html__('Medium squares', 'mesmerize'),
        'large-squares'                    => esc_html__('Large squares', 'mesmerize'),
        'small-narrow-rectangles'          => esc_html__('Small narrow rectangles', 'mesmerize'),
        'medium-narrow-rectangles'         => esc_html__('Medium narrow rectangles', 'mesmerize'),
        'large-narrow-rectangles'          => esc_html__('Large narrow rectangles', 'mesmerize'),
        'small-rounded-narrow-rectangles'  => esc_html__('Small rounded narrow rectangles', 'mesmerize'),
        'medium-rounded-narrow-rectangles' => esc_html__('Medium rounded narrow rectangles', 'mesmerize'),
        'large-rounded-narrow-rectangles'  => esc_html__('Large rounded narrow rectangles', 'mesmerize'),
        'small-wide-rectangles'            => esc_html__('Small wide rectangles', 'mesmerize'),
        'medium-wide-rectangles'           => esc_html__('Medium wide rectangles', 'mesmerize'),
        'large-wide-rectangles'            => esc_html__('Large wide rectangles', 'mesmerize'),
        'small-rounded-wide-rectangles'    => esc_html__('Small rounded wide rectangles', 'mesmerize'),
        'medium-rounded-wide-rectangles'   => esc_html__('Medium rounded wide rectangles', 'mesmerize'),
        'large-rounded-wide-rectangles'    => esc_html__('Large rounded wide rectangles', 'mesmerize'),
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_pagination',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

/*
mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_html__('Thumbnails Size', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_pagination_thumbnails_size',
    'priority'        => $priority,
    'default'         => 140,
    'choices'         => array(
        'min'  => '70',
        'max'  => '180',
        'step' => '5',
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => '.header-slider-navigation .owl-dots.thumbnails .owl-dot > *',
            'property' => 'width',
            'units'    => 'px',
            'suffix'   => '!important'
        ),
        array(
            'element'       => '.header-slider-navigation .owl-dots.thumbnails .owl-dot > *',
            'property'      => 'height',
            'value_pattern' => 'calc($px*0.5625)',
            'suffix'        => '!important'
        ),
        array(
            'element'       => '.header-slider-navigation .owl-dots.thumbnails .owl-dot .video-thumbnail-icon',
            'property'      => 'font-size',
            'value_pattern' => 'calc($px*0.5625/1.8)'
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_pagination',
            'operator' => '==',
            'value'    => true,
        ),
        array(
            'setting'  => 'slider_group_navigation',
            'operator' => '==',
            'value'    => false,
        ),
    ),
));
*/

mesmerize_add_kirki_field(array(
    'type'      => 'color',
    'label'     => esc_html__('Elements Default Color', 'mesmerize'),
    'section'   => $section,
    'settings'  => 'slider_pagination_bullets_color',
    'priority'  => $priority,
    'default'   => 'rgba(255,255,255,0.3)',
    'group'     => $group,
    'transport' => 'postMessage',
    'choices'   => array(
        'alpha' => true,
    ),
    'output'    => array(
        array(
            'element'  => '.header-slider-navigation .owl-dots .owl-dot span',
            'property' => 'background',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => '.header-slider-navigation .owl-dots .owl-dot span',
            'function' => 'css',
            'property' => 'background',
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'      => 'color',
    'label'     => esc_html__('Elements Active/Hover Color', 'mesmerize'),
    'section'   => $section,
    'settings'  => 'slider_pagination_bullets_active_color',
    'priority'  => $priority,
    'default'   => '#ffffff',
    'group'     => $group,
    'transport' => 'postMessage',
    'choices'   => array(
        'alpha' => true,
    ),
    'output'    => array(
        array(
            'element'  => array(
                '.header-slider-navigation .owl-dots .owl-dot.active span',
                '.header-slider-navigation .owl-dots .owl-dot:hover span',
            ),
            'property' => 'background',
        ),
    ),
    'js_vars'   => array(
        array(
            'element'  => array(
                '.header-slider-navigation .owl-dots .owl-dot.active span',
                '.header-slider-navigation .owl-dots .owl-dot:hover span',
            ),
            'function' => 'css',
            'property' => 'background',
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_html__('Elements Spacing', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_pagination_bullets_spacing',
    'priority'        => $priority,
    'default'         => 7,
    'choices'         => array(
        'min'  => '0',
        'max'  => '10',
        'step' => '1',
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => '.header-slider-navigation .owl-dots .owl-dot',
            'property' => 'margin',
            'units'    => 'px',
            'prefix'   => '0px ',
        ),
    ),
    'js_vars'         => array(
        array(
            'element'  => '.header-slider-navigation .owl-dots .owl-dot',
            'function' => 'css',
            'property' => 'margin',
            'units'    => 'px',
            'prefix'   => '0px ',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_pagination',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

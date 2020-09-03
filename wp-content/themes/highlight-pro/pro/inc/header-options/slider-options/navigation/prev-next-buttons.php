<?php

$section  = 'header_background_chooser';
$priority = 7;

$group = 'slider_prev_next_buttons_options_group_button';

mesmerize_add_kirki_field(array(
    'type'            => 'checkbox',
    'label'           => esc_html__('Show prev/next buttons', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_enable_prev_next_buttons',
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
    'settings'        => 'slider_prev_next_buttons_options_group_button',
    'priority'        => $priority,
    'in_row_with'     => array('slider_enable_prev_next_buttons'),
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_navigation',
            'operator' => '==',
            'value'    => true,
        ),
        array(
            'setting'  => 'slider_enable_prev_next_buttons',
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
    'label'           => esc_html__('Previous/Next Buttons Options', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_prev_next_buttons_options_separator',
    'priority'        => $priority,
    'group'           => $group,
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_prev_next_buttons',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

//mesmerize_add_kirki_field(array(
//    'type'            => 'select',
//    'label'           => esc_html__('Buttons Position', 'mesmerize'),
//    'section'         => $section,
//    'settings'        => 'slider_prev_next_buttons_position',
//    'priority'        => $priority,
//    'default'         => 'center',
//    'choices'         => array(
//        'top'    => 'top',
//        'center' => 'center',
//        'bottom' => 'bottom',
//    ),
//    'group'           => $group,
//    'transport'       => 'postMessage',
//    'active_callback' => array(
//        array(
//            'setting'  => 'slider_enable_prev_next_buttons',
//            'operator' => '==',
//            'value'    => true,
//        ),
//
//    ),
//));

mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_html__('Buttons Left/Right Spacing', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_prev_next_buttons_lr_spacing',
    'priority'        => $priority,
    'default'         => 40,
    'choices'         => array(
        'min'  => '0',
        'max'  => '200',
        'step' => '1',
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => array(
                '.header-slider-navigation.separated .owl-nav .owl-prev',
                '.header-slider-navigation.separated .owl-nav .owl-next',
            ),
            'property' => 'margin-left',
            'units'    => 'px',
        ),
        array(
            'element'  => array(
                '.header-slider-navigation.separated .owl-nav .owl-prev',
                '.header-slider-navigation.separated .owl-nav .owl-next',
            ),
            'property' => 'margin-right',
            'units'    => 'px',
        ),
    ),
    'js_vars'         => array(
        array(
            'element'  => array(
                '.header-slider-navigation.separated .owl-nav .owl-prev',
                '.header-slider-navigation.separated .owl-nav .owl-next',
            ),
            'function' => 'css',
            'property' => 'margin-left',
            'units'    => 'px',
        ),
        array(
            'element'  => array(
                '.header-slider-navigation.separated .owl-nav .owl-prev',
                '.header-slider-navigation.separated .owl-nav .owl-next',
            ),
            'function' => 'css',
            'property' => 'margin-right',
            'units'    => 'px',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_prev_next_buttons',
            'operator' => '==',
            'value'    => true,
        ),

    ),
));
//
//mesmerize_add_kirki_field(array(
//    'type'            => 'slider',
//    'label'           => esc_html__('Buttons Top Offset', 'mesmerize'),
//    'section'         => $section,
//    'settings'        => 'slider_prev_next_buttons_top_offset',
//    'priority'        => $priority,
//    'default'         => 0,
//    'choices'         => array(
//        'min'  => '0',
//        'max'  => '70',
//        'step' => '1',
//    ),
//    'group'           => $group,
//    'transport'       => 'postMessage',
//    'active_callback' => array(
//        array(
//            'setting'  => 'slider_prev_next_buttons_position',
//            'operator' => '==',
//            'value'    => 'top',
//        ),
//        array(
//            'setting'  => 'slider_enable_prev_next_buttons',
//            'operator' => '==',
//            'value'    => true,
//        ),
//
//    ),
//));

mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_html__('Buttons Offset', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_prev_next_buttons_center_offset',
    'priority'        => $priority,
    'default'         => 0,
    'choices'         => array(
        'min'  => '-80',
        'max'  => '80',
        'step' => '1',
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'active_callback' => array(

        array(
            'setting'  => 'slider_enable_prev_next_buttons',
            'operator' => '==',
            'value'    => true,
        ),

    ),
));

//mesmerize_add_kirki_field(array(
//    'type'            => 'slider',
//    'label'           => esc_html__('Buttons Bottom Offset', 'mesmerize'),
//    'section'         => $section,
//    'settings'        => 'slider_prev_next_buttons_bottom_offset',
//    'priority'        => $priority,
//    'default'         => 0,
//    'choices'         => array(
//        'min'  => '0',
//        'max'  => '70',
//        'step' => '1',
//    ),
//    'group'           => $group,
//    'transport'       => 'postMessage',
//    'active_callback' => array(
//        array(
//            'setting'  => 'slider_prev_next_buttons_position',
//            'operator' => '==',
//            'value'    => 'bottom',
//        ),
//        array(
//            'setting'  => 'slider_enable_prev_next_buttons',
//            'operator' => '==',
//            'value'    => true,
//        ),
//
//    ),
//));

mesmerize_add_kirki_field(array(
    'type'            => 'select',
    'label'           => esc_html__('Buttons Style', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_prev_next_buttons_style',
    'priority'        => $priority,
    'default'         => 'square-slider-button',
    'choices'         => array(
        'square-slider-button'         => esc_html__('square', 'mesmerize'),
        'rounded-square-slider-button' => esc_html__('rounded square', 'mesmerize'),
        'rounded-slider-button'        => esc_html__('rounded', 'mesmerize'),
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_prev_next_buttons',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_html__('Buttons Background Spacing', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_prev_next_buttons_size',
    'priority'        => $priority,
    'default'         => 0,
    'choices'         => array(
        'min'  => '0',
        'max'  => '120',
        'step' => '1',
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => array(
                '.header-slider-navigation .owl-nav .owl-prev',
                '.header-slider-navigation .owl-nav .owl-next',
            ),
            'property' => 'padding',
            'units'    => 'px',
        ),

    ),
    'js_vars'         => array(
        array(
            'element'  => array(
                '.header-slider-navigation .owl-nav .owl-prev',
                '.header-slider-navigation .owl-nav .owl-next',
            ),
            'function' => 'css',
            'property' => 'padding',
            'units'    => 'px',
        ),

    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_prev_next_buttons',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'select',
    'label'           => esc_html__('Prev/Next Buttons Icon', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_prev_next_button_icon',
    'priority'        => $priority,
    'default'         => 'fa-angle',
    'choices'         => array(
        'fa-chevron'        => esc_html__('Chevron', 'mesmerize'),
        'fa-chevron-circle' => esc_html__('Chevron Circle', 'mesmerize'),
        'fa-angle'          => esc_html__('Angle', 'mesmerize'),
        'fa-angle-double'   => esc_html__('Angle Double', 'mesmerize'),
        'fa-arrow'          => esc_html__('Arrow', 'mesmerize'),
        'fa-caret'          => esc_html__('Caret', 'mesmerize'),
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_prev_next_buttons',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_html__('Icons Size', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_prev_next_icons_size',
    'priority'        => $priority,
    'default'         => 50,
    'choices'         => array(
        'min'  => '18',
        'max'  => '120',
        'step' => '1',
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => array(
                '.header-slider-navigation .owl-nav .owl-prev i',
                '.header-slider-navigation .owl-nav .owl-next i',
            ),
            'property' => 'font-size',
            'units'    => 'px',
        ),
        array(
            'element'  => array(
                '.header-slider-navigation .owl-nav .owl-prev i',
                '.header-slider-navigation .owl-nav .owl-next i',
            ),
            'property' => 'width',
            'units'    => 'px',
        ),
        array(
            'element'  => array(
                '.header-slider-navigation .owl-nav .owl-prev i',
                '.header-slider-navigation .owl-nav .owl-next i',
            ),
            'property' => 'height',
            'units'    => 'px',
        ),
    ),
    'js_vars'         => array(
        array(
            'element'  => array(
                '.header-slider-navigation .owl-nav .owl-prev i',
                '.header-slider-navigation .owl-nav .owl-next i',
            ),
            'function' => 'css',
            'property' => 'font-size',
            'units'    => 'px',
        ),
        array(
            'element'  => array(
                '.header-slider-navigation .owl-nav .owl-prev i',
                '.header-slider-navigation .owl-nav .owl-next i',
            ),
            'function' => 'css',
            'property' => 'width',
            'units'    => 'px',
        ),
        array(
            'element'  => array(
                '.header-slider-navigation .owl-nav .owl-prev i',
                '.header-slider-navigation .owl-nav .owl-next i',
            ),
            'function' => 'css',
            'property' => 'height',
            'units'    => 'px',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_prev_next_buttons',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'sectionseparator',
    'label'           => esc_html__('Buttons Colors', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_prev_next_buttons_colors_separator',
    'priority'        => $priority,
    'group'           => $group,
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_prev_next_buttons',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'color',
    'label'           => esc_html__('Icons Color', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_prev_next_buttons_icon_color',
    'priority'        => $priority,
    'default'         => '#ffffff',
    'choices'         => array(
        'alpha' => true,
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => array(
                '.header-slider-navigation .owl-nav .owl-prev i',
                '.header-slider-navigation .owl-nav .owl-next i',
            ),
            'property' => 'color',
        ),
    ),
    'js_vars'         => array(
        array(
            'element'  => array(
                '.header-slider-navigation .owl-nav .owl-prev i',
                '.header-slider-navigation .owl-nav .owl-next i',
            ),
            'function' => 'css',
            'property' => 'color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_prev_next_buttons',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'color',
    'label'           => esc_html__('Background Color', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_prev_next_buttons_background_color',
    'priority'        => $priority,
    'default'         => 'rgba(0, 0, 0, 0)',
    'choices'         => array(
        'alpha' => true,
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => array(
                '.header-slider-navigation .owl-nav .owl-prev',
                '.header-slider-navigation .owl-nav .owl-next',
            ),
            'property' => 'background',
        ),
    ),
    'js_vars'         => array(
        array(
            'element'  => array(
                '.header-slider-navigation .owl-nav .owl-prev',
                '.header-slider-navigation .owl-nav .owl-next',
            ),
            'function' => 'css',
            'property' => 'background',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_prev_next_buttons',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'color',
    'label'           => esc_html__('Background Hover Color', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_prev_next_buttons_background_hover_color',
    'priority'        => $priority,
    'default'         => 'rgba(0, 0, 0, 0)',
    'choices'         => array(
        'alpha' => true,
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => array(
                '.header-slider-navigation .owl-nav .owl-prev:hover',
                '.header-slider-navigation .owl-nav .owl-next:hover',
            ),
            'property' => 'background',
        ),
    ),
    'js_vars'         => array(
        array(
            'element'  => array(
                '.header-slider-navigation .owl-nav .owl-prev:hover',
                '.header-slider-navigation .owl-nav .owl-next:hover',
            ),
            'function' => 'css',
            'property' => 'background',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_prev_next_buttons',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

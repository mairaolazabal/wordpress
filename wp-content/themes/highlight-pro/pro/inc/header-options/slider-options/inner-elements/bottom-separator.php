<?php

$section  = 'header_background_chooser';
$priority = 7;

$group = 'slider_bottom_separator_options_group_button';

mesmerize_add_kirki_field(array(
    'type'            => 'checkbox',
    'label'           => esc_html__('Use Bottom Separator', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_show_separator',
    'priority'        => $priority,
    'default'         => false,
    'active_callback' => array(
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
    'settings'        => 'slider_bottom_separator_options_group_button',
    'priority'        => $priority,
    'in_row_with'     => array('slider_show_separator'),
    'active_callback' => array(
        array(
            'setting'  => 'slider_show_separator',
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
    'label'           => esc_html__('Bottom Separator Options', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_bottom_separator_options_separator',
    'priority'        => $priority,
    'group'           => $group,
    'active_callback' => array(
        array(
            'setting'  => 'slider_show_separator',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'select',
    'label'           => esc_html__('Type', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_bottom_separator_type',
    'priority'        => $priority,
    'default'         => 'mesmerize/1.wave-and-line',
    'choices'         => mesmerize_get_separators_list(),
    'group'           => $group,
    'active_callback' => array(
        array(
            'setting'  => 'slider_show_separator',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'color',
    'label'           => esc_html__('Color', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_bottom_separator_color',
    'priority'        => $priority,
    'default'         => '#ffffff',
    'choices'         => array(
        'alpha' => true,
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => '.header-with-slider-wrapper .header-separator .svg-white-bg',
            'property' => 'fill',
            'suffix'   => '!important',
        ),
    ),
    'js_vars'         => array(
        array(
            'element'  => '.header-with-slider-wrapper .header-separator .svg-white-bg',
            'property' => 'fill',
            'suffix'   => '!important',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_show_separator',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'color',
    'label'           => esc_html__('Accent Color', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slide_bottom_separator_color_accent',
    'priority'        => $priority,
    'default'         => mesmerize_get_theme_colors('color2'),
    'choices'         => array(
        'alpha' => true,
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => '.header-with-slider-wrapper .header-separator path.svg-accent',
            'property' => 'stroke',
            'suffix'   => '!important',
        ),
    ),
    'js_vars'         => array(
        array(
            'element'  => '.header-with-slider-wrapper .header-separator path.svg-accent',
            'property' => 'stroke',
            'suffix'   => '!important',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_show_separator',
            'operator' => '==',
            'value'    => true,
        ),
        array(
            'setting'  => 'slider_bottom_separator_type',
            'operator' => 'in',
            'value'    => mesmerize_get_2_colors_separators(array(), true),
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_html__('Height', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_bottom_separator_height',
    'priority'        => $priority,
    'default'         => 154,
    'choices'         => array(
        'min'  => '0',
        'max'  => '400',
        'step' => '1',
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => '.header-with-slider-wrapper .header-separator svg',
            'property' => 'height',
            'suffix'   => '!important',
            'units'    => 'px',
        ),
    ),
    'js_vars'         => array(
        array(
            'element'  => '.header-with-slider-wrapper .header-separator svg',
            'function' => 'css',
            'property' => 'height',
            'units'    => 'px',
            'suffix'   => '!important',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_show_separator',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

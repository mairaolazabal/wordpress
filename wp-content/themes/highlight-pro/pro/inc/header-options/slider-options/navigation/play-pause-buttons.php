<?php

$section  = 'header_background_chooser';
$priority = 7;

$group = 'slider_play_pause_button_options_group_button';

mesmerize_add_kirki_field(array(
    'type'            => 'checkbox',
    'label'           => esc_html__('Show play/pause button', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_enable_play_pause_button',
    'priority'        => $priority,
    'default'         => false,
    'transport'       => 'postMessage',
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_navigation',
            'operator' => '==',
            'value'    => true,
        ),
        array(
            'setting'  => 'slider_enable_autoplay',
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
    'settings'        => 'slider_play_pause_button_options_group_button',
    'priority'        => $priority,
    'in_row_with'     => array('slider_enable_play_pause_button'),
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_navigation',
            'operator' => '==',
            'value'    => true,
        ),
        array(
            'setting'  => 'slider_enable_play_pause_button',
            'operator' => '==',
            'value'    => true,
        ),
        array(
            'setting'  => 'slider_enable_autoplay',
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
    'label'           => esc_html__('Play/Pause Button Options', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_play_pause_button_options_separator',
    'priority'        => $priority,
    'group'           => $group,
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_play_pause_button',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'select',
    'label'           => esc_html__('Button Position', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_play_pause_button_position',
    'priority'        => $priority,
    'default'         => 'right bottom',
    'choices'         => array(
        "left top"      => esc_html__('Left Top', 'mesmerize'),
        "left bottom"   => esc_html__('Left Bottom', 'mesmerize'),
        "center top"    => esc_html__('Center Top', 'mesmerize'),
        "center bottom" => esc_html__('Center Bottom', 'mesmerize'),
        "right top"     => esc_html__('Right Top', 'mesmerize'),
        "right bottom"  => esc_html__('Right Bottom', 'mesmerize'),
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_play_pause_button',
            'operator' => '==',
            'value'    => true,
        ),

    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_html__('Button Left/Right Spacing', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_play_pause_button_lr_spacing',
    'priority'        => $priority,
    'default'         => 10,
    'choices'         => array(
        'min'  => '0',
        'max'  => '50',
        'step' => '1',
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => '.header-slider-navigation.separated .owl-nav .owl-autoplay',
            'property' => 'margin-left',
            'units'    => 'px',
        ),
        array(
            'element'  => '.header-slider-navigation.separated .owl-nav .owl-autoplay',
            'property' => 'margin-right',
            'units'    => 'px',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_play_pause_button',
            'operator' => '==',
            'value'    => true,
        ),

        array(
            'setting'  => 'slider_play_pause_button_position',
            'operator' => 'in',
            'value'    => array('left top', 'left bottom', 'right top', 'right bottom'),
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_html__('Button Top Offset', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_play_pause_button_top_offset',
    'priority'        => $priority,
    'default'         => 0,
    'choices'         => array(
        'min'  => '0',
        'max'  => '70',
        'step' => '1',
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'active_callback' => array(
        array(
            'setting'  => 'slider_play_pause_button_position',
            'operator' => 'in',
            'value'    => array('left top', 'center top', 'right top'),
        ),
        array(
            'setting'  => 'slider_enable_play_pause_button',
            'operator' => '==',
            'value'    => true,
        ),

    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_html__('Button Bottom Offset', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_play_pause_button_bottom_offset',
    'priority'        => $priority,
    'default'         => 10,
    'choices'         => array(
        'min'  => '0',
        'max'  => '70',
        'step' => '1',
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'active_callback' => array(
        array(
            'setting'  => 'slider_play_pause_button_position',
            'operator' => 'in',
            'value'    => array('left bottom', 'center bottom', 'right bottom'),
        ),
        array(
            'setting'  => 'slider_enable_play_pause_button',
            'operator' => '==',
            'value'    => true,
        ),

    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'select',
    'label'           => esc_html__('Button Style', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_play_pause_button_style',
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
            'setting'  => 'slider_enable_play_pause_button',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_html__('Button Background Spacing', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_play_pause_button_size',
    'priority'        => $priority,
    'default'         => 0,
    'choices'         => array(
        'min'  => '0',
        'max'  => '70',
        'step' => '1',
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => '.header-slider-navigation .owl-nav .owl-autoplay',
            'property' => 'padding',
            'units'    => 'px',
        ),

    ),
    'js_vars'         => array(
        array(
            'element'  => '.header-slider-navigation .owl-nav .owl-autoplay',
            'property' => 'padding',
            'units'    => 'px',
            'function' => 'css',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_play_pause_button',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_html__('Icon Size', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_play_pause_icon_size',
    'priority'        => $priority,
    'default'         => 36,
    'choices'         => array(
        'min'  => '18',
        'max'  => '80',
        'step' => '1',
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => '.header-slider-navigation .owl-nav .owl-autoplay i',
            'property' => 'font-size',
            'units'    => 'px',
        ),

        array(
            'element'  => '.header-slider-navigation .owl-nav .owl-autoplay i',
            'property' => 'width',
            'units'    => 'px',
        ),

        array(
            'element'  => '.header-slider-navigation .owl-nav .owl-autoplay i',
            'property' => 'height',
            'units'    => 'px',
        ),
    ),
    'js_vars'         => array(
        array(
            'element'  => '.header-slider-navigation .owl-nav .owl-autoplay i',
            'function' => 'css',
            'property' => 'font-size',
            'units'    => 'px',
        ),

        array(
            'element'  => '.header-slider-navigation .owl-nav .owl-autoplay i',
            'property' => 'width',
            'function' => 'css',
            'units'    => 'px',
        ),

        array(
            'element'  => '.header-slider-navigation .owl-nav .owl-autoplay i',
            'property' => 'height',
            'function' => 'css',
            'units'    => 'px',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_play_pause_button',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'sectionseparator',
    'label'           => esc_html__('Button Colors', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_play_pause_button_colors_separator',
    'priority'        => $priority,
    'group'           => $group,
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_play_pause_button',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'color',
    'label'           => esc_html__('Background Color', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_play_pause_button_background_color',
    'priority'        => $priority,
    'default'         => 'rgba(0, 0, 0, 0)',
    'choices'         => array(
        'alpha' => true,
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => '.header-slider-navigation .owl-nav .owl-autoplay',
            'property' => 'background',
        ),
    ),
    'js_vars'         => array(
        array(
            'element'  => '.header-slider-navigation .owl-nav .owl-autoplay',
            'function' => 'css',
            'property' => 'background',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_play_pause_button',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'color',
    'label'           => esc_html__('Background Hover Color', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_play_pause_button_background_hover_color',
    'priority'        => $priority,
    'default'         => 'rgba(0, 0, 0, 0)',
    'choices'         => array(
        'alpha' => true,
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => '.header-slider-navigation .owl-nav .owl-autoplay:hover',
            'property' => 'background',
        ),
    ),
    'js_vars'         => array(
        array(
            'element'  => '.header-slider-navigation .owl-nav .owl-autoplay:hover',
            'function' => 'css',
            'property' => 'background',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_play_pause_button',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'color',
    'label'           => esc_html__('Pause Icon Color', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_pause_button_icon_color',
    'priority'        => $priority,
    'default'         => 'rgba(255,255,255,0.8)',
    'choices'         => array(
        'alpha' => true,
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'js_vars'         => array(
        array(
            'element'  => '.header-slider-navigation .owl-nav .owl-autoplay.is-playing i',
            'function' => 'css',
            'property' => 'color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_play_pause_button',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'color',
    'label'           => esc_html__('Play Icon Color', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_play_button_icon_color',
    'priority'        => $priority,
    'default'         => '#ffffff',
    'choices'         => array(
        'alpha' => true,
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'js_vars'         => array(
        array(
            'element'  => '.header-slider-navigation .owl-nav .owl-autoplay i',
            'function' => 'css',
            'property' => 'color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_play_pause_button',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

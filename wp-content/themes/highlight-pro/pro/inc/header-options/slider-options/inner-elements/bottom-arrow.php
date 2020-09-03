<?php

$section  = 'header_background_chooser';
$priority = 7;

$group = 'slider_bottom_arrow_options_group_button';

mesmerize_add_kirki_field(array(
    'type'            => 'checkbox',
    'label'           => esc_html__('Use Bottom Arrow', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_show_bottom_arrow',
    'priority'        => $priority,
    'default'         => false,
    'transport'       => 'postMessage',
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
    'settings'        => $group,
    'priority'        => $priority,
    'in_row_with'     => array('slider_show_bottom_arrow'),
    'active_callback' => array(
        array(
            'setting'  => 'slider_show_bottom_arrow',
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
    'label'           => esc_html__('Bottom Arrow Options', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_bottom_arrow_options_separator',
    'priority'        => $priority,
    'group'           => $group,
    'active_callback' => array(
        array(
            'setting'  => 'slider_show_bottom_arrow',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'checkbox',
    'label'           => esc_html__('Bounce arrow', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_bottom_arrow_bounce',
    'priority'        => $priority,
    'transport'       => 'postMessage',
    'default'         => true,
    'group'           => $group,
    'active_callback' => array(
        array(
            'setting'  => 'slider_show_bottom_arrow',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'font-awesome-icon-control',
    'label'           => esc_html__('Icon', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_bottom_arrow_icon',
    'priority'        => $priority,
    'transport'       => 'postMessage',
    'default'         => 'fa-angle-down',
    'group'           => $group,
    'active_callback' => array(
        array(
            'setting'  => 'slider_show_bottom_arrow',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_html__('Icon Size', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_bottom_arrow_icon_size',
    'priority'        => $priority,
    'default'         => '50',
    'choices'         => array(
        'min'  => '10',
        'max'  => '100',
        'step' => '1',
    ),
    'priority'        => $priority,
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => '.header-with-slider-wrapper .header-homepage-arrow',
            'property' => 'font-size',
            'suffix'   => 'px',
        ),
        array(
            'element'  => '.header-with-slider-wrapper .header-homepage-arrow > i',
            'property' => 'width',
            'suffix'   => 'px',
        ),
        array(
            'element'  => '.header-with-slider-wrapper .header-homepage-arrow > i',
            'property' => 'height',
            'suffix'   => 'px',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_show_bottom_arrow',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_html__('Icon Bottom Offset', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_bottom_arrow_icon_bottom_offset',
    'priority'        => $priority,
    'default'         => '5',
    'choices'         => array(
        'min'  => '0',
        'max'  => '200',
        'step' => '1',
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => '.header-with-slider-wrapper .header-homepage-arrow',
            'property' => 'bottom',
            'suffix'   => 'px',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_show_bottom_arrow',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'color',
    'label'           => esc_html__('Icon Color', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_bottom_arrow_icon_color',
    'priority'        => $priority,
    'default'         => '#ffffff',
    'choices'         => array(
        'alpha' => true,
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => '.header-with-slider-wrapper .header-homepage-arrow > i',
            'property' => 'color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_show_bottom_arrow',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'color',
    'label'           => esc_html__('Icon Background Color', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_bottom_arrow_icon_background_color',
    'priority'        => $priority,
    'default'         => 'rgba(255,255,255,0)',
    'choices'         => array(
        'alpha' => true,
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => '.header-with-slider-wrapper .header-homepage-arrow',
            'property' => 'background',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_show_bottom_arrow',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

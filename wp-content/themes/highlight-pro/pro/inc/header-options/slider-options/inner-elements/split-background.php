<?php

$section = 'header_background_chooser';
$priority = 7;

$group = 'slider_split_background_options_group_button';

mesmerize_add_kirki_field(array(
    'type'     => 'checkbox',
    'label'    => esc_html__('Use Split Background', 'mesmerize'),
    'section'  => $section,
    'settings' =>'slider_use_split_background',
    'priority' => $priority,
    'default'  => false,
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
    'in_row_with'     => array('slider_use_split_background'),
    'active_callback' => array(
        array(
            'setting'  => 'slider_use_split_background',
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
    'label'           => esc_html__('Split Background Options', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_split_background_options_separator',
    'priority'        => $priority,
    'group'           => $group,
    'active_callback' => array(
        array(
            'setting'  => 'slider_use_split_background',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'color',
    'label'           => esc_attr__('Color', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_split_background_color',
    'priority'        => $priority,
    'default'         => '#000000',
    'choices'         => array(
        'alpha' => true,
    ),
    'group'           => $group,
    'transport' => 'postMessage',
    'active_callback' => array(
        array(
            'setting'  => 'slider_use_split_background',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_attr__('Angle', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_split_background_angle',
    'priority'        => $priority,
    'default'         => 0,
    'choices'         => array(
        'min'  => '-180',
        'max'  => '180',
        'step' => '5',
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'active_callback' => array(
        array(
            'setting'  => 'slider_use_split_background',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_attr__('Size', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_split_background_size',
    'priority'        => $priority,
    'default'         => 50,
    'choices'         => array(
        'min'  => '0',
        'max'  => '100',
        'step' => '1',
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'active_callback' => array(
        array(
            'setting'  => 'slider_use_split_background',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_attr__('Angle on mobile devices', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_split_background_angle_mobile',
    'priority'        => $priority,
    'default'         => 90,
    'choices'         => array(
        'min'  => '-180',
        'max'  => '180',
        'step' => '5',
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'active_callback' => array(
        array(
            'setting'  => 'slider_use_split_background',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_attr__('Size on mobile devices', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_split_background_size_mobile',
    'priority'        => $priority,
    'default'         => 50,
    'choices'         => array(
        'min'  => '0',
        'max'  => '100',
        'step' => '1',
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'active_callback' => array(
        array(
            'setting'  => 'slider_use_split_background',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

<?php

$section  = 'header_background_chooser';
$priority = 7;

mesmerize_add_kirki_field(array(
    'type'            => 'sectionseparator',
    'label'           => esc_html__('Slides Transitions', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_transitions_separator',
    'priority'        => $priority,
    'active_callback' => array(
        array(
            'setting'  => 'header_type',
            'operator' => '==',
            'value'    => 'slider',
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'checkbox',
    'label'           => esc_html__('Enable Rewind', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_enable_rewind',
    'priority'        => $priority,
    'default'         => true,
    'transport'       => 'postMessage',
    'active_callback' => array(
        array(
            'setting'  => 'header_type',
            'operator' => '==',
            'value'    => 'slider',
        ),
    ),
));


// Autoplay controls

$group = 'slider_autoplay_options_group_button';

mesmerize_add_kirki_field(array(
    'type'            => 'checkbox',
    'label'           => esc_html__('Enable Autoplay', 'mesmerize'),
    'description'     => esc_html__('Autoplay is disabled while you are in customizer', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_enable_autoplay',
    'priority'        => $priority,
    'default'         => true,
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
    'settings'        => 'slider_autoplay_options_group_button',
    'priority'        => $priority,
    'in_row_with'     => array('slider_enable_autoplay'),
    'active_callback' => array(
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
    'label'           => esc_html__('Autoplay Options', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_autoplay_options_separator',
    'priority'        => $priority,
    'group'           => $group,
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_autoplay',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'number',
    'label'           => esc_html__('Slide Duration (ms)', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_transitions_duration',
    'priority'        => $priority,
    'group'           => $group,
    'default'         => 7000,
    'choices'         => array(
        'min'  => 1000,
        'max'  => 30000,
        'step' => 50,
    ),
    'transport'       => 'postMessage',
    'active_callback' => array(
        array(
            'setting'  => 'slider_enable_autoplay',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'      => 'checkbox',
    'label'     => esc_html__('Show Progress Bar', 'mesmerize'),
    'section'   => $section,
    'settings'  => 'slider_show_progress_bar',
    'priority'  => $priority,
    'group'     => $group,
    'default'   => true,
    'transport' => 'postMessage',
));

mesmerize_add_kirki_field(array(
    'type'            => 'color',
    'label'           => esc_html__('Bar Color', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_progress_bar_color',
    'priority'        => $priority,
    'default'         => 'rgba(3, 169, 244, 0.5)',
    'choices'         => array(
        'alpha' => true,
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => '.slide-progress',
            'property' => 'background',
        ),
    ),
    'js_vars'         => array(
        array(
            'element'  => '.slide-progress',
            'function' => 'css',
            'property' => 'background',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_show_progress_bar',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'slider',
    'label'           => esc_html__('Bar Height', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_progress_bar_height',
    'priority'        => $priority,
    'default'         => 5,
    'choices'         => array(
        'min'  => '2',
        'max'  => '10',
        'step' => '1',
    ),
    'group'           => $group,
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => '.slide-progress',
            'property' => 'height',
            'units'    => 'px',
        ),
    ),
    'js_vars'         => array(
        array(
            'element'  => '.slide-progress',
            'function' => 'css',
            'property' => 'height',
            'units'    => 'px',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'slider_show_progress_bar',
            'operator' => '==',
            'value'    => true,
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'number',
    'label'           => esc_html__('Animation Duration (ms)', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_animations_duration',
    'priority'        => $priority,
    'default'         => 1000,
    'choices'         => array(
        'min'  => 500,
        'max'  => 5000,
        'step' => 100,
    ),
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => '.owl-carousel .animated',
            'property' => 'animation-duration',
            'suffix'   => 'ms',
        ),
        array(
            'element'  => '.owl-carousel .animated',
            'property' => '-webkit-animation-duration',
            'suffix'   => 'ms',
        ),
    ),
    'js_vars'         => array(
        array(
            'element'  => '.owl-carousel .animated',
            'function' => 'css',
            'property' => 'animation-duration',
            'suffix'   => 'ms',
        ),
        array(
            'element'  => '.owl-carousel .animated',
            'function' => 'css',
            'property' => '-webkit-animation-duration',
            'suffix'   => 'ms',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'header_type',
            'operator' => '==',
            'value'    => 'slider',
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'select',
    'label'           => esc_html__('Slide Animation Effect', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_animation_effect',
    'priority'        => $priority,
    'default'         => 'horizontal',
    'choices'         => array(
        'horizontal'    => esc_html__('Slide horizontal', 'mesmerize'),
        'vertical-down' => esc_html__('Slide down vertical', 'mesmerize'),
        'vertical-up'   => esc_html__('Slide up vertical', 'mesmerize'),
        'fade'          => esc_html__('Fade', 'mesmerize'),
        'zoom'          => esc_html__('Zoom', 'mesmerize'),
        'flip'          => esc_html__('Flip', 'mesmerize'),
//        'custom'        => 'Custom',
    ),
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
    'type'            => 'select',
    'label'           => esc_html__('Slide AnimateOut Effect', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_transitions_out_effect',
    'priority'        => $priority,
    'default'         => 'slideOutDown',
    'choices'         => mesmerize_get_transition_effect_list('out'),
    'transport'       => 'postMessage',
    'active_callback' => array(
        array(
            'setting'  => 'slider_animation_effect',
            'operator' => '==',
            'value'    => 'custom',
        ),
        array(
            'setting'  => 'header_type',
            'operator' => '==',
            'value'    => 'slider',
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'select',
    'label'           => esc_html__('Slide AnimateIn Effect', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_transitions_in_effect',
    'priority'        => $priority,
    'default'         => 'slideInDown',
    'choices'         => mesmerize_get_transition_effect_list('in'),
    'transport'       => 'postMessage',
    'active_callback' => array(
        array(
            'setting'  => 'slider_animation_effect',
            'operator' => '==',
            'value'    => 'custom',
        ),
        array(
            'setting'  => 'header_type',
            'operator' => '==',
            'value'    => 'slider',
        ),
    ),
));

mesmerize_add_kirki_field(array(
    'type'            => 'color',
    'label'           => esc_html__('Transition Background', 'mesmerize'),
    'section'         => $section,
    'settings'        => 'slider_transitions_background',
    'priority'        => $priority,
    'default'         => '#000000',
    'choices'         => array(
        'alpha' => false,
    ),
    'transport'       => 'postMessage',
    'output'          => array(
        array(
            'element'  => array(
                '#header-slides-container .owl-stage',
            ),
            'property' => 'background',
        ),
    ),
    'js_vars'         => array(
        array(
            'element'  => array(
                '#header-slides-container .owl-stage',
            ),
            'function' => 'css',
            'property' => 'background',
        ),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'header_type',
            'operator' => '==',
            'value'    => 'slider',
        ),
    ),
));

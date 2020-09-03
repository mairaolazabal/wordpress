<?php

add_filter('mesmerize_inner_pages_header_content_options_after', function($section, $prefix, $priority) {

    mesmerize_add_kirki_field(array(
        'type'            => 'sidebar-button-group',
        'settings' => 'inner_header_content_title_group',
        'label'    => esc_html__('Title Options', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        "choices"  => array(
            "inner_header_content_title_typography",
        )
    ));

    mesmerize_add_kirki_field(array(
        'type'      => 'typography',
        'settings'  => 'inner_header_content_title_typography',
        'label'     => __('Title Typography', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'default'   => array(
            'font-family'      => apply_filters('mesmerize_default_headings_font_family', 'Muli'),
            'font-size'        => '3.5em',
            'mobile-font-size' => '3.5em',
            'variant'          => '300',
            'line-height'      => '114%',
            'letter-spacing'   => '0.9px',
            'subsets'          => array(),
            'color'            => '#ffffff',
            'text-transform'   => 'none',
            'addwebfont'       => true,
        ),
        'output'    => array(
            array(
                'element' => '.inner-header-description h1.hero-title',
            ),
        ),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element' => ".inner-header-description h1.hero-title",
            ),
        ),
    ));
}, 2, 3);

add_filter('mesmerize_inner_pages_header_content_options_after', function($section, $prefix, $priority) {

    mesmerize_add_kirki_field(array(
        'type'            => 'sidebar-button-group',
        'settings'        => 'inner_header_content_subtitle_group',
        'label'           => esc_html__('Subtitle Options', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        "choices"         => array(
            "inner_header_content_subtitle_typography",
        ),
        'active_callback' => array(
            array(
                'setting'  => 'inner_header_show_subtitle',
                'operator' => '==',
                'value'    => true,
            ),
        ),
        'in_row_with' => array('inner_header_show_subtitle')
    ));

    mesmerize_add_kirki_field(array(
        'type'      => 'typography',
        'settings'  => 'inner_header_content_subtitle_typography',
        'label'     => __('Subtitle Typography', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'default'   => array(
            'font-family'      => apply_filters('mesmerize_default_headings_font_family', 'Muli'),
            'font-size'        => '1.3em',
            'mobile-font-size' => '1.3em',
            'variant'          => '300',
            'line-height'      => '130%',
            'letter-spacing'   => '0px',
            'subsets'          => array(),
            'color'            => '#ffffff',
            'text-transform'   => 'none',
            'addwebfont'       => true,
        ),
        'output'    => array(
            array(
                'element' => '.inner-header-description .header-subtitle',
            ),
        ),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element' => ".inner-header-description .header-subtitle",
            ),
        ),
    ));
    
}, 1, 3);

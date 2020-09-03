<?php

add_filter("header_content_subtitle_group_filter", function ($values) {
    $new = array(
        "header_content_subtitle_typography",
        "header_content_subtitle_spacing",
        "header_content_subtitle_background_options_separator",
        "header_content_subtitle_background_color",
        "header_content_subtitle_background_spacing",
        "header_content_subtitle_background_border_radius",
    );

    return array_merge($values, $new);
});

add_action("mesmerize_front_page_header_subtitle_options_after", function ($section, $prefix, $priority) {

    mesmerize_add_kirki_field(array(
        'type'      => 'spacing',
        'settings'  => 'header_content_subtitle_spacing',
        'label'     => __('Subtitle Spacing', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'default'   => array(
            'top'    => '0',
            'bottom' => '20px',
        ),
        'transport' => 'postMessage',
        'output'    => array(
            array(
                'element'  => '.header-homepage p.header-subtitle',
                'property' => 'margin',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'  => ".header-homepage p.header-subtitle",
                'function' => 'style',
                'property' => 'margin',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'      => 'typography',
        'settings'  => 'header_content_subtitle_typography',
        'label'     => __('Subtitle Typography', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'default'   => array(
            'font-family'      => apply_filters('mesmerize_default_subtitle_font_family', 'Muli'),
            'font-size'        => '1.3em',
            'mobile-font-size' => '1.3em',
            'font-weight'      => '300',
            'line-height'      => '130%',
            'letter-spacing'   => '0px',
            'subsets'          => array(),
            'color'            => '#ffffff',
            'text-transform'   => 'none',
            'addwebfont'       => true,
        ),
        'output'    => array(
            array(
                'element' => '.header-homepage p.header-subtitle',
            ),
        ),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element' => '.header-homepage p.header-subtitle',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => esc_html__('Background Options', 'mesmerize'),
        'section'  => $section,
        'settings' => "header_content_subtitle_background_options_separator",
        'priority' => $priority,
    ));

    mesmerize_add_kirki_field(array(
        'type'      => 'color',
        'label'     => esc_html__('Background Color', 'mesmerize'),
        'section'   => $section,
        'settings'  => 'header_content_subtitle_background_color',
        'default'   => 'rgba(0,0,0,0)',
        'transport' => 'postMessage',
        'priority'  => $priority,
        'choices'   => array(
            'alpha' => true,
        ),
        "output"    => array(
            array(
                'element'  => '.header-homepage p.header-subtitle',
                'property' => 'background',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'  => ".header-homepage p.header-subtitle",
                'function' => 'css',
                'property' => 'background',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'      => 'spacing',
        'settings'  => 'header_content_subtitle_background_spacing',
        'label'     => esc_html__('Background Spacing', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'default'   => array(
            'top'    => '0px',
            'bottom' => '0px',
            'left'   => '0px',
            'right'  => '0px',
        ),
        'transport' => 'postMessage',
        'output'    => array(
            array(
                'element'  => '.header-homepage p.header-subtitle',
                'property' => 'padding',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'  => ".header-homepage p.header-subtitle",
                'function' => 'style',
                'property' => 'padding',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'      => 'dimension',
        'settings'  => 'header_content_subtitle_background_border_radius',
        'label'     => esc_html__('Border Radius', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'default'   => '0px',
        'transport' => 'postMessage',
        'output'    => array(
            array(
                'element'  => '.header-homepage p.header-subtitle',
                'property' => 'border-radius',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'  => ".header-homepage p.header-subtitle",
                'function' => 'style',
                'property' => 'border-radius',
            ),
        ),
    ));


}, 1, 3);

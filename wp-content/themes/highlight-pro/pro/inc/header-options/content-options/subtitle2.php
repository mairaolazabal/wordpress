<?php

add_action("mesmerize_front_page_header_title_options_before", "mesmerize_front_page_header_title_options_before", 1, 3);
function mesmerize_front_page_header_title_options_before($section, $prefix, $priority)
{
    mesmerize_add_options_group(array(
        "mesmerize_front_page_header_subtitle_2_options" => array(
            // section, prefix, priority
            "header_background_chooser",
            "header",
            6,
        ),
    ));
}

add_filter("header_content_subtitle2_group_filter", function ($values) {
    $new = array(
        "header_content_subtitle2_typography",
        "header_content_subtitle_spacing2",
        "header_content_subtitle2_background_options_separator",
        "header_content_subtitle2_background_color",
        "header_content_subtitle2_background_spacing",
        "header_content_subtitle2_background_border_radius",
    );

    return array_merge($values, $new);
});
function mesmerize_front_page_header_subtitle_2_options($section, $prefix, $priority)
{
    $companion = apply_filters('mesmerize_is_companion_installed', false);
    /*
    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => __('Motto ( before title )', 'mesmerize'),
        'section'  => $section,
        'settings' => "content_subtitle_separator2",
        'priority' => $priority,
    ));*/
    
    mesmerize_add_kirki_field(array(
        'type'            => 'checkbox',
        'settings'        => 'header_content_show_subtitle2',
        'label'           => __('Show Motto', 'mesmerize'),
        'section'         => $section,
        'default'         => false,
        'priority'        => $priority,
        'active_callback' => apply_filters('mesmerize_header_active_callback_filter', array(), false),
    ));
    
    mesmerize_add_kirki_field(array(
        'type'            => 'sidebar-button-group',
        'settings'        => 'header_content_subtitle2_group',
        'label'           => esc_html__('Options', 'mesmerize'),
        'section'         => $section,
        'priority'        => $priority,
        "choices"         => array(
            "header_subtitle2",
        ),
        'active_callback' => apply_filters('mesmerize_header_active_callback_filter', array(
            array(
                'setting'  => 'header_content_show_subtitle2',
                'operator' => '==',
                'value'    => true,
            ),

        ), false),
        'in_row_with'     => array('header_content_show_subtitle2'),
    ));

    if ( ! $companion) {
        mesmerize_add_kirki_field(array(
            'type'              => 'text',
            'settings'          => 'header_subtitle2',
            'label'             => __('Motto', 'mesmerize'),
            'section'           => $section,
            'default'           => "",
            'sanitize_callback' => 'wp_kses_post',
            'priority'          => $priority,
            'partial_refresh'   => array(
                'header_subtitle2' => array(
                    'selector'        => ".header-homepage .header-subtitle2",
                    'render_callback' => function () {
                        return get_theme_mod('header_subtitle');
                    },
                ),
            ),
        ));
    }
}

add_action("mesmerize_front_page_header_subtitle_2_options_after", function ($section, $prefix, $priority) {

    mesmerize_add_kirki_field(array(
        'type'      => 'spacing',
        'settings'  => 'header_content_subtitle_spacing2',
        'label'     => __('Motto Spacing', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'default'   => array(
            'top'    => '0',
            'bottom' => '20px',
        ),
        'transport' => 'postMessage',
        'output'    => array(
            array(
                'element'  => '.header-homepage p.header-subtitle2',
                'property' => 'margin',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'  => ".header-homepage p.header-subtitle2",
                'function' => 'style',
                'property' => 'margin',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'      => 'typography',
        'settings'  => 'header_content_subtitle2_typography',
        'label'     => __('Motto Typography', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'default'   => array(
            'font-family'      => 'Roboto',
            'font-size'        => '1.4em',
            'mobile-font-size' => '1.4em',
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
                'element' => '.header-homepage p.header-subtitle2',
            ),
        ),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element' => '.header-homepage p.header-subtitle2',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => esc_html__('Background Options', 'mesmerize'),
        'section'  => $section,
        'settings' => "header_content_subtitle2_background_options_separator",
        'priority' => $priority,
    ));

    mesmerize_add_kirki_field(array(
        'type'      => 'color',
        'label'     => esc_html__('Background Color', 'mesmerize'),
        'section'   => $section,
        'settings'  => 'header_content_subtitle2_background_color',
        'default'   => 'rgba(0,0,0,0)',
        'transport' => 'postMessage',
        'priority'  => $priority,
        'choices'   => array(
            'alpha' => true,
        ),
        "output"    => array(
            array(
                'element'  => '.header-homepage p.header-subtitle2',
                'property' => 'background',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'  => ".header-homepage p.header-subtitle2",
                'function' => 'css',
                'property' => 'background',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'      => 'spacing',
        'settings'  => 'header_content_subtitle2_background_spacing',
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
                'element'  => '.header-homepage p.header-subtitle2',
                'property' => 'padding',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'  => ".header-homepage p.header-subtitle2",
                'function' => 'style',
                'property' => 'padding',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'      => 'dimension',
        'settings'  => 'header_content_subtitle2_background_border_radius',
        'label'     => esc_html__('Border Radius', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'default'   => '0px',
        'transport' => 'postMessage',
        'output'    => array(
            array(
                'element'  => '.header-homepage p.header-subtitle2',
                'property' => 'border-radius',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'  => ".header-homepage p.header-subtitle2",
                'function' => 'style',
                'property' => 'border-radius',
            ),
        ),
    ));


}, 1, 3);

add_action("mesmerize_print_header_content", function () {
    mesmerize_print_header_subtitle2();
}, 0);


function mesmerize_print_header_subtitle2()
{
    $subtitle = get_theme_mod('header_subtitle2', "");
    $show     = get_theme_mod('header_content_show_subtitle2', false);

    if (mesmerize_can_show_demo_content()) {
        if ($subtitle == "") {
            $subtitle = __('You can set this subtitle from the customizer.', 'mesmerize');
        }
    }
    if ($show) {
        printf('<p class="header-subtitle2">%1$s</p>', mesmerize_wp_kses_post($subtitle));
    }
}

<?php

add_action("mesmerize_top_bar_social_icons_fields_options_before", function($area, $section, $priority, $prefix) {
    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => __('Social Icons Colors', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        'settings' => "{$prefix}_social_fields_colors_separator",
    ));

    mesmerize_add_kirki_field(array(
        'type'    => 'color',
        'label'   => __('Icon Color', 'mesmerize'),
        'section' => $section,
        'settings'  => "{$prefix}_social_icons_options_icon_color",
        'default'   => "#fff",
        'priority'  => $priority,
        'choices'   => array(
            'alpha' => false,
        ),
        'transport' => 'postMessage',
        "output" => array(
            array(
                'element'  => ".header-top-bar .header-top-bar-area.{$area} .top-bar-social-icons i",
                'property' => 'color',
            ),
        ),
        'js_vars' => array(
            array(
                'element'  => ".header-top-bar .header-top-bar-area.{$area} .top-bar-social-icons i",
                'property' => 'color',
                'function' => 'css',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'    => 'color',
        'label'   => __('Icon Color on Hover', 'mesmerize'),
        'section' => $section,
        'settings'  => "{$prefix}_social_icons_options_icon_hover_color",
        'default'   => "#fff",
        'priority'  => $priority,
        'choices'   => array(
            'alpha' => false,
        ),
        'transport' => 'postMessage',
        "output" => array(
            array(
                'element'  => ".header-top-bar .header-top-bar-area.{$area} .top-bar-social-icons i:hover",
                'property' => 'color',
            ),
        ),
        'js_vars' => array(
            array(
                'element'  => ".header-top-bar .header-top-bar-area.{$area} .top-bar-social-icons i:hover",
                'property' => 'color',
                'function' => 'css',
            ),
        ),
    ));

},1, 4);


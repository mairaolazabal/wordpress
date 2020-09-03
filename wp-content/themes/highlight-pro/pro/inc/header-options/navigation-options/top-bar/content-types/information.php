<?php

add_action("mesmerize_top_bar_information_fields_options_before", function($area, $section, $priority, $prefix) {
	mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => __('Information fields colors', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        'settings' => "{$prefix}_info_fields_colors_separator",
    ));

    mesmerize_add_kirki_field(array(
        'type'    => 'color',
        'label'   => __('Text Color', 'mesmerize'),
        'section' => $section,
        'settings'  => "{$prefix}_information_fields_text_color",
        'default'   => "#FFFFFF",
        'priority'  => $priority,
        'choices'   => array(
            'alpha' => false,
        ),
        'transport' => 'postMessage',
        "output" => array(
            array(
                'element'  => ".header-top-bar .header-top-bar-area.{$area} span",
                'property' => 'color',
            ),
        ),
        'js_vars' => array(
            array(
                'element'  => ".header-top-bar .header-top-bar-area.{$area} span",
                'property' => 'color',
                'function' => 'css',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'    => 'color',
        'label'   => __('Icon Color', 'mesmerize'),
        'section' => $section,
        'settings'  => "{$prefix}_information_fields_icon_color",
        'default'   => "#999",
        'priority'  => $priority,
        'choices'   => array(
            'alpha' => false,
        ),
        'transport' => 'postMessage',
        "output" => array(
            array(
                'element'  => ".header-top-bar .header-top-bar-area.{$area} i.fa",
                'property' => 'color',
            ),
        ),
        'js_vars' => array(
            array(
                'element'  => ".header-top-bar .header-top-bar-area.{$area} i.fa",
                'property' => 'color',
                'function' => 'css',
            ),
        ),
    ));

}, 1, 4);

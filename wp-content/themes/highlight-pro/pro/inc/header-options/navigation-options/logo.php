<?php


// LOGO SETTINGS SETTINGS - START


function mesmerize_text_logo_settings($inner = false)
{
    $priority = 3;
    $section  = "navigation_logo";
    $prefix   = $inner ? "inner_header" : "header";


    $separatorLabel = $inner ? __('Inner Page Logo Settings', 'mesmerize') : __('Front Page Logo Settings', 'mesmerize');

    mesmerize_add_kirki_field(array(
        'type'     => 'sectionseparator',
        'label'    => $separatorLabel,
        'settings' => "{$prefix}_nav_logo_separator",
        'section'  => $section,
        'priority' => $priority,
    ));

    $transparent_nav = get_theme_mod($prefix . '_nav_transparent', mesmerize_mod_default("{$prefix}_nav_transparent"));

    if ($transparent_nav) {
        $default_logo_color = mesmerize_get_var("header_text_logo_color");
    } else {
        $default_logo_color = mesmerize_get_var("header_text_logo_sticky_color");
    }

    mesmerize_add_kirki_field(array(
        'type'     => 'color',
        'settings' => "{$prefix}_header_text_logo_color",
        'label'    => esc_attr__('Text logo color', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        'default'  => $default_logo_color,
        'choices'  => array(
            'alpha' => false,
        ),
        'output'   => array(
            array(
                'element'  => mesmerize_get_nav_text_logo_selector($inner, $prefix),
                'property' => 'color',
            ),

        ),

        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => mesmerize_get_nav_text_logo_selector($inner, $prefix),
                'property' => 'color',
            ),
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'     => 'color',
        'settings' => "{$prefix}_header_text_logo_sticky_color",
        'label'    => esc_attr__('Sticky nav text logo color', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        'default'  => mesmerize_get_var("header_text_logo_sticky_color"),
        'choices'  => array(
            'alpha' => false,
        ),
        'output'   => array(
            array(
                'element'  => mesmerize_get_sticky_nav_text_logo_selector($inner),
                'property' => 'color',
                'suffix'   => '!important',
            ),

        ),

        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => mesmerize_get_sticky_nav_text_logo_selector($inner),
                'property' => 'color',
                'suffix'   => '!important',
            ),
        ),
    ));


    mesmerize_add_kirki_field(array(
        'type'     => 'sidebar-button-group',
        'settings' => "{$prefix}_header_nav_logo_typo_group",
        'label'    => __('Text Logo Typography', 'mesmerize'),
        'section'  => $section,
        'priority' => $priority,
        "choices"  => array(
            "{$prefix}_text_logo_typography",
        ),
    ));

    mesmerize_add_kirki_field(array(
        'type'      => 'typography',
        'settings'  => "{$prefix}_text_logo_typography",
        'label'     => __('Text Logo Typography', 'mesmerize'),
        'section'   => $section,
        'priority'  => $priority,
        'default'   => array(
            'font-family'    => 'inherit',
            'font-size'      => '1.6rem',
            'font-weight'    => '600',
            'line-height'    => '100%',
            'letter-spacing' => '0px',
            'subsets'        => array(),
            'text-transform' => 'uppercase',
            'addwebfont'     => true,
        ),
        'output'    => array(
            array(
                'element' => mesmerize_get_nav_text_logo_selector($inner, $prefix),
            ),
        ),
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element' => mesmerize_get_nav_text_logo_selector($inner, $prefix),
            ),
        ),
    ));


}

add_action("mesmerize_customize_register_options", function() {
    mesmerize_text_logo_settings(false);
    mesmerize_text_logo_settings(true);
});

// LOGO SETTINGS SETTINGS - END
